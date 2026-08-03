<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockRequest;
use App\Models\User;
use App\Notifications\StockRequestSubmittedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class StockRequestController extends Controller
{
    public function index(Request $request): View
    {
        $stockRequests = StockRequest::with('product')
            ->where('client_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('stock_requests.index', compact('stockRequests'));
    }

    public function create(): View
    {
        $products = Product::where('status', Product::STATUS_ACTIVE)->get();
        return view('stock_requests.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'requested_quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $stockRequest = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
            'requested_quantity' => $validated['requested_quantity'],
            'notes' => $validated['notes'] ?? null,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        // Notify Admins
        $admins = User::role(['Super Admin', 'Admin'])->get();
        Notification::send($admins, new StockRequestSubmittedNotification($stockRequest));

        return redirect()->route('stock-requests.index')
            ->with('success', "Stock Request #{$stockRequest->request_number} submitted to Ceylon AG management.");
    }
}
