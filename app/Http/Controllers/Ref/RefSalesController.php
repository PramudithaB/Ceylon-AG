<?php

namespace App\Http\Controllers\Ref;

use App\Exceptions\InsufficientClientStockException;
use App\Http\Controllers\Controller;
use App\Models\ClientSale;
use App\Models\User;
use App\Services\ClientSaleService;
use App\Services\RefCrmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefSalesController extends Controller
{
    /**
     * Display listing of client sales.
     */
    public function index(Request $request)
    {
        $refUser = Auth::user();
        $assignedClientIds = $refUser->assignedClients()->pluck('id');
        if ($assignedClientIds->isEmpty()) {
            $assignedClientIds = User::where('role', User::ROLE_CLIENT)->pluck('id');
        }

        $query = ClientSale::whereIn('client_id', $assignedClientIds)->with(['client', 'product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $sales = $query->latest('sold_at')->paginate(15)->withQueryString();
        $clients = User::whereIn('id', $assignedClientIds)->get();

        return view('ref.sales.index', compact('sales', 'clients'));
    }

    /**
     * Show form to record a new retail sale for a client.
     */
    public function create(Request $request, RefCrmService $crmService)
    {
        $refUser = Auth::user();
        $assignedClients = $refUser->assignedClients()->where('status', User::STATUS_APPROVED)->get();
        if ($assignedClients->isEmpty()) {
            $assignedClients = User::where('role', User::ROLE_CLIENT)->whereIn('status', [User::STATUS_APPROVED, User::STATUS_ACTIVE])->get();
        }

        $activeClientId = $request->input('client_id', session('active_client_id'));
        $selectedClient = null;
        $availableProducts = collect();

        if ($activeClientId) {
            $selectedClient = $assignedClients->firstWhere('id', (int) $activeClientId)
                ?? User::where('id', (int) $activeClientId)->first();

            if ($selectedClient) {
                $availableProducts = $crmService->getClientProductStock($selectedClient)
                    ->filter(fn ($item) => $item->remaining_qty > 0);
            }
        }

        return view('ref.sales.create', compact('assignedClients', 'selectedClient', 'availableProducts'));
    }

    /**
     * Store new retail sale for a client.
     */
    public function store(Request $request, ClientSaleService $saleService)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
            'sold_at' => ['nullable', 'date'],
        ]);

        $client = User::findOrFail($validated['client_id']);
        if (! ($client->isClient() || $client->role === User::ROLE_CLIENT || $client->hasRole('Client'))) {
            return redirect()->back()
                ->withErrors(['client_id' => 'Sales can only be recorded for client accounts. Ref and Admin accounts cannot be recipients.'])
                ->withInput();
        }

        $validated['sold_at'] = $validated['sold_at'] ?? now();

        try {
            $sale = $saleService->recordSale($validated, $client);

            flash_message("Sale #{$sale->sale_number} of {$sale->quantity} units successfully recorded for {$client->name}.", 'success');

            if ($request->input('source') === 'dashboard' || $request->has('from_dashboard')) {
                return redirect()->route('ref.dashboard', ['client_id' => $client->id])
                    ->with('success', "Sale #{$sale->sale_number} recorded successfully!");
            }

            return redirect()->route('ref.sales.index');
        } catch (InsufficientClientStockException $e) {
            flash_message($e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display sales reports and summaries.
     */
    public function reports(Request $request)
    {
        $refUser = Auth::user();
        $assignedClientIds = $refUser->assignedClients()->pluck('id');
        if ($assignedClientIds->isEmpty()) {
            $assignedClientIds = User::where('role', User::ROLE_CLIENT)->pluck('id');
        }

        $totalSalesCount = ClientSale::whereIn('client_id', $assignedClientIds)->count();
        $totalRevenue = ClientSale::whereIn('client_id', $assignedClientIds)->sum('total_amount');
        $totalItemsSold = ClientSale::whereIn('client_id', $assignedClientIds)->sum('quantity');

        $salesByClient = ClientSale::whereIn('client_id', $assignedClientIds)
            ->select('client_id', \DB::raw('SUM(total_amount) as revenue'), \DB::raw('SUM(quantity) as items'), \DB::raw('COUNT(id) as sales_count'))
            ->groupBy('client_id')
            ->with('client')
            ->orderByDesc('revenue')
            ->get();

        return view('ref.sales.reports', compact('totalSalesCount', 'totalRevenue', 'totalItemsSold', 'salesByClient'));
    }
}
