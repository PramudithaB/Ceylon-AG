<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\ClientSale;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StockRequest;
use App\Models\User;
use App\Services\ClientSaleService;
use App\Services\RefCrmService;
use Illuminate\Http\Request;

class RefDashboardController extends Controller
{
    public function __construct(
        protected RefCrmService $crmService,
        protected ClientSaleService $saleService
    ) {}

    /**
     * Render Streamlined Mobile-First Ref Dashboard.
     */
    public function index(Request $request)
    {
        $refUser = auth()->user();

        // 1. Fetch available clients for the selector
        $assignedClients = $this->crmService->getAssignedClients($refUser);
        $allClients = $this->crmService->getAllClientsForSelector();
        $clientList = $assignedClients->isNotEmpty() ? $assignedClients : $allClients;
        $clientIds = $clientList->pluck('id');

        // 2. Resolve Active Client (from query param or session)
        if ($request->has('client_id')) {
            $clientId = $request->input('client_id');
            if ($clientId) {
                session(['active_client_id' => (int) $clientId]);
            } else {
                session()->forget('active_client_id');
            }
        }

        $activeClientId = session('active_client_id');
        $selectedClient = null;

        if ($activeClientId) {
            $selectedClient = $clientList->firstWhere('id', (int) $activeClientId)
                ?? User::where('id', (int) $activeClientId)
                    ->where(function ($q) {
                        $q->where('role', User::ROLE_CLIENT)
                          ->orWhereHas('roles', fn ($rq) => $rq->where('name', 'client'));
                    })->first();
        }

        // 3. Active client metrics and assigned products (for sales recording)
        $clientSummary = null;
        $clientAssignedProducts = collect();
        if ($selectedClient) {
            $clientSummary = $this->crmService->getClientFinancialSummary($selectedClient);
            $clientAssignedProducts = $this->crmService->getClientProductStock($selectedClient)
                ->filter(fn ($item) => $item->remaining_qty > 0);
        }

        // 4. Warehouse active products (for stock request form)
        $products = Product::where('status', 'active')->orderBy('name')->get();

        // 5. Status counters for this Ref's portfolio
        $statusCounts = [
            'pending_requests' => StockRequest::whereIn('client_id', $clientIds)->where('status', StockRequest::STATUS_PENDING)->count(),
            'approved_requests' => StockRequest::whereIn('client_id', $clientIds)->where('status', StockRequest::STATUS_APPROVED)->count(),
            'pending_payments' => Payment::whereIn('client_id', $clientIds)->where('status', Payment::STATUS_PENDING)->count(),
            'total_sales_count' => ClientSale::whereIn('client_id', $clientIds)->count(),
        ];

        // 6. Unified Recent Activity Feed (Stock Requests, Sales, Payments)
        $recentStockRequests = StockRequest::with(['client', 'product'])
            ->whereIn('client_id', $clientIds)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($item) => [
                'type' => 'stock_request',
                'title' => 'Stock Request #' . $item->request_number,
                'client_name' => $item->client->business_name ?? $item->client->name,
                'product_name' => $item->product->name ?? 'Product',
                'detail' => $item->requested_quantity . ' units',
                'status' => $item->status,
                'timestamp' => $item->created_at,
                'date_formatted' => $item->created_at->format('M d, H:i'),
                'icon' => 'cube',
            ]);

        $recentSales = ClientSale::with(['client', 'product'])
            ->whereIn('client_id', $clientIds)
            ->latest('sold_at')
            ->take(5)
            ->get()
            ->map(fn ($item) => [
                'type' => 'sale',
                'title' => 'Retail Sale #' . $item->sale_number,
                'client_name' => $item->client->business_name ?? $item->client->name,
                'product_name' => $item->product->name ?? 'Product',
                'detail' => $item->quantity . ' units (LKR ' . number_format($item->total_amount, 2) . ')',
                'status' => 'completed',
                'timestamp' => $item->sold_at,
                'date_formatted' => $item->sold_at->format('M d, H:i'),
                'icon' => 'shopping-cart',
            ]);

        $recentPayments = Payment::with('client')
            ->whereIn('client_id', $clientIds)
            ->latest('payment_date')
            ->take(5)
            ->get()
            ->map(fn ($item) => [
                'type' => 'payment',
                'title' => 'Payment #' . $item->payment_number,
                'client_name' => $item->client->business_name ?? $item->client->name,
                'product_name' => ucfirst(str_replace('_', ' ', $item->payment_method ?? 'Cash')),
                'detail' => 'LKR ' . number_format($item->amount, 2),
                'status' => $item->status,
                'timestamp' => $item->created_at ?? $item->payment_date,
                'date_formatted' => \Carbon\Carbon::parse($item->payment_date)->format('M d, Y'),
                'icon' => 'credit-card',
            ]);

        $recentActivity = $recentStockRequests
            ->concat($recentSales)
            ->concat($recentPayments)
            ->sortByDesc('timestamp')
            ->take(8)
            ->values();

        // 7. Recent stock requests specifically for table view
        $recentRequests = StockRequest::with(['client', 'product'])
            ->whereIn('client_id', $clientIds)
            ->latest()
            ->take(6)
            ->get();

        return view('ref.dashboard', [
            'refUser' => $refUser,
            'clients' => $clientList,
            'assignedClients' => $assignedClients,
            'allClients' => $allClients,
            'selectedClient' => $selectedClient,
            'clientSummary' => $clientSummary,
            'clientAssignedProducts' => $clientAssignedProducts,
            'products' => $products,
            'statusCounts' => $statusCounts,
            'recentActivity' => $recentActivity,
            'recentRequests' => $recentRequests,
        ]);
    }

    /**
     * AJAX/POST Endpoint to switch active client.
     */
    public function selectClient(Request $request)
    {
        $request->validate([
            'client_id' => ['required', 'exists:users,id'],
        ]);

        $client = User::findOrFail($request->client_id);
        if (! ($client->isClient() || $client->role === User::ROLE_CLIENT || $client->hasRole('Client'))) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Selected user is not a valid client.'], 422);
            }
            return redirect()->back()->withErrors(['client_id' => 'Selected user is not a valid client.']);
        }

        session(['active_client_id' => $client->id]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'client_id' => $client->id,
                'name' => $client->name,
                'business_name' => $client->business_name ?? $client->name,
                'phone' => $client->phone ?? 'N/A',
                'district' => $client->district ?? 'N/A',
            ]);
        }

        return redirect()->route('ref.dashboard')
            ->with('success', "Active client set to {$client->business_name} ({$client->name}).");
    }

    /**
     * Clear active client session.
     */
    public function clearClient(Request $request)
    {
        session()->forget('active_client_id');

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('ref.dashboard')
            ->with('info', 'Active client selection cleared.');
    }
}
