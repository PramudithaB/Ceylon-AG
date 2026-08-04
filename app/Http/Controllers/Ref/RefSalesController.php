<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\ClientSale;
use App\Models\User;
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
