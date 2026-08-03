<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Notifications\StockRequestStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminStockRequestController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');

        $query = StockRequest::with(['client', 'product', 'reviewer']);

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

        $stockRequest->update([
            'status' => StockRequest::STATUS_APPROVED,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        // Notify Client
        $stockRequest->client->notify(new StockRequestStatusNotification($stockRequest));

        return redirect()->back()->with('success', "Stock Request #{$stockRequest->request_number} approved successfully.");
    }

    public function reject(Request $request, StockRequest $stockRequest): RedirectResponse
    {
        if (! $stockRequest->isPending()) {
            return redirect()->back()->with('error', 'This stock request has already been reviewed.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $stockRequest->update([
            'status' => StockRequest::STATUS_REJECTED,
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        // Notify Client
        $stockRequest->client->notify(new StockRequestStatusNotification($stockRequest));

        return redirect()->back()->with('success', "Stock Request #{$stockRequest->request_number} rejected.");
    }
}
