<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefStockRequestController extends Controller
{
    /**
     * Display stock requests list.
     */
    public function index(Request $request)
    {
        $refUser = Auth::user();
        $assignedClientIds = $refUser->assignedClients()->pluck('id');
        if ($assignedClientIds->isEmpty()) {
            $assignedClientIds = User::where('role', User::ROLE_CLIENT)->pluck('id');
        }

        $query = StockRequest::whereIn('client_id', $assignedClientIds)->with(['client', 'product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $stockRequests = $query->latest()->paginate(10)->withQueryString();

        return view('ref.stock-requests.index', compact('stockRequests'));
    }

    /**
     * Show form to submit new stock request on behalf of a client.
     */
    public function create(Request $request)
    {
        $refUser = Auth::user();
        $clients = $refUser->assignedClients()->where('status', User::STATUS_APPROVED)->get();
        if ($clients->isEmpty()) {
            $clients = User::where('role', User::ROLE_CLIENT)->whereIn('status', [User::STATUS_APPROVED, User::STATUS_ACTIVE])->get();
        }

        $selectedClientId = $request->input('client_id', session('active_client_id'));
        $products = Product::where('status', 'active')->get();

        return view('ref.stock-requests.create', compact('clients', 'products', 'selectedClientId'));
    }

    /**
     * Store new stock request in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'requested_quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $client = User::findOrFail($request->client_id);
        if (! ($client->isClient() || $client->role === User::ROLE_CLIENT || $client->hasRole('Client'))) {
            return redirect()->back()
                ->withErrors(['client_id' => 'Stock requests can only be created for client accounts. Ref and Admin accounts cannot be recipients.'])
                ->withInput();
        }

        $stockRequest = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $client->id,
            'product_id' => $request->product_id,
            'requested_quantity' => $request->requested_quantity,
            'notes' => $request->notes ? "[Submitted by Ref: " . Auth::user()->name . "] " . $request->notes : "Submitted by Ref: " . Auth::user()->name,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        // Notify Admins
        try {
            $admins = User::where('role', User::ROLE_ADMIN)
                ->orWhereHas('roles', fn ($q) => $q->whereIn('name', ['Admin', 'Super Admin']))
                ->get();
            if ($admins->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewStockRequestSubmittedNotification($stockRequest));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed sending stock request notification for #{$stockRequest->request_number}: " . $e->getMessage());
        }

        flash_message("Stock request #{$stockRequest->request_number} submitted successfully to Admin for approval.", 'success');

        if ($request->input('source') === 'dashboard' || $request->has('from_dashboard')) {
            return redirect()->route('ref.dashboard', ['client_id' => $client->id])
                ->with('success', "Stock request #{$stockRequest->request_number} submitted to Admin successfully!");
        }

        return redirect()->route('ref.stock-requests.index');
    }
}
