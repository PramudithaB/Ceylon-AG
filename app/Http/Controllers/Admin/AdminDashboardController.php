<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientSale;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\StockRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Control Center with real database metrics and clean empty states.
     */
    public function index(Request $request): View
    {
        // 1. Revenue & Sales Metrics
        $totalRevenue = (float) ClientSale::sum('total_amount');
        $totalSalesCount = ClientSale::count();
        $todaySalesCount = ClientSale::whereDate('sold_at', today())->count();
        $todayRevenue = (float) ClientSale::whereDate('sold_at', today())->sum('total_amount');

        // 2. Payments Metrics (Approved vs Pending)
        $verifiedPaymentsAmount = (float) Payment::where('status', Payment::STATUS_APPROVED)->sum('amount');
        $pendingPaymentsCount = Payment::where('status', Payment::STATUS_PENDING)->count();

        // 3. User & Portfolio Metrics
        $activeClientsCount = User::where('role', User::ROLE_CLIENT)->where('status', User::STATUS_APPROVED)->count();
        $pendingClientsCount = User::where('role', User::ROLE_CLIENT)->where('status', User::STATUS_PENDING)->count();
        $activeRefsCount = User::where('role', User::ROLE_REF)->count();

        // 4. Products & Stock Metrics
        $activeProductsCount = Product::where('status', 'active')->count();
        $totalStockOnHand = (int) Product::where('status', 'active')->sum('stock_quantity');
        $lowStockProductsCount = Product::where('status', 'active')
            ->whereColumn('stock_quantity', '<=', 'minimum_stock')
            ->count();

        // 5. Stock Requests
        $pendingStockRequestsCount = StockRequest::where('status', StockRequest::STATUS_PENDING)->count();
        $approvedStockRequestsCount = StockRequest::where('status', StockRequest::STATUS_APPROVED)->count();

        // 6. Quotations
        $totalQuotationsCount = Quotation::count();
        $pendingQuotationsCount = Quotation::whereIn('status', [Quotation::STATUS_SENT, Quotation::STATUS_DRAFT])->count();

        // 7. Recent Sales List (Real DB data)
        $recentSales = ClientSale::with(['client', 'product'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        // 8. Pending Approvals (Stock Requests & Payments)
        $pendingStockRequests = StockRequest::with(['client', 'product'])
            ->where('status', StockRequest::STATUS_PENDING)
            ->latest()
            ->take(5)
            ->get();

        $pendingPayments = Payment::with('client')
            ->where('status', Payment::STATUS_PENDING)
            ->latest()
            ->take(5)
            ->get();

        // 9. Recent Quotations
        $recentQuotations = Quotation::with(['client', 'creator'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'metrics' => [
                'total_revenue' => $totalRevenue,
                'total_sales_count' => $totalSalesCount,
                'today_sales_count' => $todaySalesCount,
                'today_revenue' => $todayRevenue,
                'verified_payments_amount' => $verifiedPaymentsAmount,
                'pending_payments_count' => $pendingPaymentsCount,
                'active_clients_count' => $activeClientsCount,
                'pending_clients_count' => $pendingClientsCount,
                'active_refs_count' => $activeRefsCount,
                'active_products_count' => $activeProductsCount,
                'total_stock_on_hand' => $totalStockOnHand,
                'low_stock_products_count' => $lowStockProductsCount,
                'pending_stock_requests_count' => $pendingStockRequestsCount,
                'approved_stock_requests_count' => $approvedStockRequestsCount,
                'total_quotations_count' => $totalQuotationsCount,
                'pending_quotations_count' => $pendingQuotationsCount,
            ],
            'recentSales' => $recentSales,
            'pendingStockRequests' => $pendingStockRequests,
            'pendingPayments' => $pendingPayments,
            'recentQuotations' => $recentQuotations,
        ]);
    }
}
