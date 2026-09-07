<?php

namespace App\Http\Controllers\Ref;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefProductAssignmentController extends Controller
{
    public function __construct(
        protected ProductAssignmentService $assignmentService
    ) {}

    /**
     * Handle Product Assignment initiated by Ref for a client.
     */
    public function store(Request $request)
    {
        $clientId = $request->input('client_id') ?? $request->input('user_id');
        $request->merge(['client_id' => $clientId, 'user_id' => $clientId]);

        $request->validate([
            'client_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $refUser = Auth::user();
        $recipient = User::findOrFail($request->client_id);

        // Security / Authorization: strictly role == client
        if ($recipient->role !== User::ROLE_CLIENT || $recipient->isAdmin() || $recipient->isRef() || $recipient->hasAnyRole(['Admin', 'Super Admin', 'Ref'])) {
            return redirect()->back()
                ->withErrors([
                    'client_id' => 'Products can only be assigned to client accounts. Ref and Admin users cannot receive product assignments.',
                    'user_id' => 'Products can only be assigned to client accounts. Ref and Admin users cannot receive product assignments.',
                ])
                ->withInput();
        }

        // Prevent assigning to self or other refs
        if ($recipient->id === $refUser->id) {
            return redirect()->back()
                ->withErrors([
                    'client_id' => 'You cannot assign products to your own account.',
                    'user_id' => 'You cannot assign products to your own account.',
                ])
                ->withInput();
        }

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock_quantity) {
            return redirect()->back()
                ->withErrors(['quantity' => "Cannot assign {$request->quantity} units. Only {$product->stock_quantity} units available in warehouse stock."])
                ->withInput();
        }

        $assignmentData = [
            'client_id' => $recipient->id,
            'product_id' => $product->id,
            'quantity' => (int) $request->quantity,
            'dealer_price' => $product->dealer_price,
            'selling_price' => $product->selling_price,
            'notes' => $request->notes ? "[Assigned by Ref: {$refUser->name}] " . $request->notes : "Assigned by Ref: {$refUser->name}",
        ];

        try {
            $assignment = $this->assignmentService->assignProduct($assignmentData, $refUser);
            session(['active_client_id' => $recipient->id]);

            flash_message("Product '{$product->name}' ({$assignment->quantity} units) successfully assigned to {$recipient->business_name} (#{$assignment->assignment_number}).", 'success');

            return redirect()->route('ref.dashboard', ['client_id' => $recipient->id])
                ->with('success', "Product '{$product->name}' ({$assignment->quantity} units) successfully assigned to {$recipient->business_name}.");
        } catch (InsufficientStockException $e) {
            return redirect()->back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withErrors(['client_id' => $e->getMessage()])->withInput();
        }
    }
}
