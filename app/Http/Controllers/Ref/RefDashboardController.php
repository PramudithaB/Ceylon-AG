<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\ClientSale;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefDashboardController extends Controller
{
    public function index()
    {
        $refUser = Auth::user();

        // Get clients assigned to this Ref user, or fallback to all active client users if none explicitly assigned
        $assignedClientIds = $refUser->assignedClients()->pluck('id');
        if ($assignedClientIds->isEmpty()) {
            $assignedClientIds = User::where('role', User::ROLE_CLIENT)
                ->whereIn('status', [User::STATUS_APPROVED, User::STATUS_ACTIVE])
                ->pluck('id');
        }

        // Dashboard Card Data
        $assignedClientsCount = count($assignedClientIds);

        $assignedProductsCount = ProductAssignment::whereIn('client_id', $assignedClientIds)
            ->distinct('product_id')
            ->count('product_id');

        if ($assignedProductsCount === 0) {
            $assignedProductsCount = Product::where('status', 'active')->count();
        }

        $currentMonthSales = ClientSale::whereIn('client_id', $assignedClientIds)
            ->whereMonth('sold_at', now()->month)
            ->whereYear('sold_at', now()->year)
            ->sum('total_amount');

        $pendingPaymentsCount = Payment::whereIn('client_id', $assignedClientIds)
            ->where('status', 'pending')
            ->count();

        // Commission placeholder (5% of current month sales)
        $totalCommission = $currentMonthSales * 0.05;

        // Chart Data 1: Monthly Sales (Last 6 Months)
        $monthlySalesChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $total = ClientSale::whereIn('client_id', $assignedClientIds)
                ->whereMonth('sold_at', $date->month)
                ->whereYear('sold_at', $date->year)
                ->sum('total_amount');

            $monthlySalesChart['labels'][] = $monthName;
            $monthlySalesChart['data'][] = (float) $total;
        }

        // Chart Data 2: Client Performance (Top Clients by Total Sales Volume)
        $clientPerformance = ClientSale::whereIn('client_id', $assignedClientIds)
            ->select('client_id', DB::raw('SUM(total_amount) as total_sales'))
            ->groupBy('client_id')
            ->with('client:id,name,business_name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        $clientPerformanceChart = [
            'labels' => $clientPerformance->map(fn($item) => $item->client->business_name ?? $item->client->name ?? 'Client')->toArray(),
            'data' => $clientPerformance->map(fn($item) => (float) $item->total_sales)->toArray(),
        ];

        // Recent Activity / Sales
        $recentSales = ClientSale::whereIn('client_id', $assignedClientIds)
            ->with(['client', 'product'])
            ->latest('sold_at')
            ->limit(5)
            ->get();

        return view('ref.dashboard', compact(
            'refUser',
            'assignedClientsCount',
            'assignedProductsCount',
            'currentMonthSales',
            'pendingPaymentsCount',
            'totalCommission',
            'monthlySalesChart',
            'clientPerformanceChart',
            'recentSales'
        ));
    }
}
