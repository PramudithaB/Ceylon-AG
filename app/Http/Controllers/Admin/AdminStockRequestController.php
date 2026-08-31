<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\StockRequest;
use App\Notifications\ProductAssignedNotification;
use App\Notifications\StockRequestStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminStockRequestController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');

        $query = StockRequest::with(['client.salesRep', 'product', 'reviewer']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $stockRequests = $query->latest()->paginate(15);

        return view('admin.stock_requests.index', compact('stockRequests', 'status'));
    }

    public function approve(Request $request, StockRequest $stockRequest): RedirectResponse
    {
        if (! $stockRequest->isPending()) {
            return redirect()->back()->with('error', 'This stock request has already been reviewed.');
        }

        try {
            DB::transaction(function () use ($request, $stockRequest) {
                // Lock stock request row for update to prevent concurrent duplicate processing
                $lockedRequest = StockRequest::lockForUpdate()->findOrFail($stockRequest->id);

                if (! $lockedRequest->isPending()) {
                    throw new \Exception('This stock request has already been reviewed or assigned.');
                }

                // Verify recipient is a valid client
                if (! $lockedRequest->client || ! ($lockedRequest->client->isClient() || $lockedRequest->client->role === \App\Models\User::ROLE_CLIENT || $lockedRequest->client->hasRole('Client'))) {
                    throw new \Exception("Invalid recipient. Stock requests can only be approved and assigned for client accounts.");
                }

                // Lock product row for update to prevent race conditions in warehouse inventory
                $product = Product::lockForUpdate()->findOrFail($lockedRequest->product_id);

                // Verify warehouse stock availability
                if ($lockedRequest->requested_quantity > $product->stock_quantity) {
                    throw new InsufficientStockException(
                        "Cannot approve request #{$lockedRequest->request_number}. Requested quantity ({$lockedRequest->requested_quantity}) exceeds available warehouse stock ({$product->stock_quantity})."
                    );
                }

                // 1. Deduct warehouse inventory
                $product->decrement('stock_quantity', $lockedRequest->requested_quantity);

                // 2. Create automatic Product Assignment record for the Client
                $assignment = ProductAssignment::create([
                    'assignment_number' => ProductAssignment::generateAssignmentNumber(),
                    'client_id' => $lockedRequest->client_id,
                    'product_id' => $product->id,
                    'assigned_by' => $request->user()->id,
                    'quantity' => $lockedRequest->requested_quantity,
                    'dealer_price' => (float) ($product->dealer_price ?? 0),
                    'selling_price' => (float) ($product->selling_price ?? 0),
                    'total_dealer_amount' => (float) ($lockedRequest->requested_quantity * ($product->dealer_price ?? 0)),
                    'notes' => "Auto-assigned upon approval of Stock Request #{$lockedRequest->request_number}" . ($lockedRequest->notes ? " [Notes: {$lockedRequest->notes}]" : ''),
                    'assigned_at' => now(),
                ]);

                // 3. Mark stock request as approved
                $lockedRequest->update([
                    'status' => StockRequest::STATUS_APPROVED,
                    'reviewed_by' => $request->user()->id,
                    'reviewed_at' => now(),
                ]);

                // 4. Send notifications to Client
                try {
                    $lockedRequest->client->notify(new StockRequestStatusNotification($lockedRequest));
                    $assignment->load(['client', 'product']);
                    $lockedRequest->client->notify(new ProductAssignedNotification($assignment));
                } catch (\Throwable $e) {
                    Log::error("Failed sending stock request status/assignment notification #{$lockedRequest->request_number}: " . $e->getMessage(), ['exception' => $e]);
                }
            });

            flash_message("Stock Request #{$stockRequest->request_number} approved and {$stockRequest->requested_quantity} units automatically assigned to {$stockRequest->client->name} successfully!", 'success');

            return redirect()->back()->with('success', "Stock Request #{$stockRequest->request_number} approved and {$stockRequest->requested_quantity} units automatically assigned to {$stockRequest->client->name} successfully!");
        } catch (InsufficientStockException $e) {
            flash_message($e->getMessage(), 'error');
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            flash_message('Error approving stock request: ' . $e->getMessage(), 'error');
            return redirect()->back()->with('error', 'Error approving stock request: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, StockRequest $stockRequest): RedirectResponse
    {
        if (! $stockRequest->isPending()) {
            return redirect()->back()->with('error', 'This stock request has already been reviewed.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($request, $stockRequest, $validated) {
                $lockedRequest = StockRequest::lockForUpdate()->findOrFail($stockRequest->id);

                if (! $lockedRequest->isPending()) {
                    throw new \Exception('This stock request has already been reviewed.');
                }

                $lockedRequest->update([
                    'status' => StockRequest::STATUS_REJECTED,
                    'rejection_reason' => $validated['rejection_reason'],
                    'reviewed_by' => $request->user()->id,
                    'reviewed_at' => now(),
                ]);

                // Notify Client
                try {
                    $lockedRequest->client->notify(new StockRequestStatusNotification($lockedRequest));
                } catch (\Throwable $e) {
                    Log::error("Failed sending stock request status notification #{$lockedRequest->request_number}: " . $e->getMessage(), ['exception' => $e]);
                }
            });

            flash_message("Stock Request #{$stockRequest->request_number} rejected.", 'warning');

            return redirect()->back()->with('success', "Stock Request #{$stockRequest->request_number} rejected.");
        } catch (\Throwable $e) {
            flash_message('Error rejecting stock request: ' . $e->getMessage(), 'error');
            return redirect()->back()->with('error', 'Error rejecting stock request: ' . $e->getMessage());
        }
    }
}
