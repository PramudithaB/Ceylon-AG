<?php

namespace App\Http\Controllers\Client;

use App\Exceptions\InsufficientClientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientSaleRequest;
use App\Services\ClientSaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function __construct(
        protected ClientSaleService $saleService
    ) {}

    /**
     * Display a listing of client sales history.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $filters = [
            'search' => $request->get('search'),
            'product_id' => $request->get('product_id', 'all'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $sales = $this->saleService->getClientSales($user, $filters, 15);
        $summary = $this->saleService->getClientSummary($user);
        $assignedProducts = $this->saleService->getClientInventoryBreakdown($user);

        return view('sales.index', [
            'sales' => $sales,
            'summary' => $summary,
            'assignedProducts' => $assignedProducts,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for recording a new product sale.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $assignedProducts = $this->saleService->getClientInventoryBreakdown($user)
            ->filter(fn ($item) => $item['remaining_qty'] > 0);

        return view('sales.create', [
            'assignedProducts' => $assignedProducts,
            'selectedProductId' => $request->get('product_id'),
        ]);
    }

    /**
     * Store a newly recorded sale in storage.
     */
    public function store(StoreClientSaleRequest $request): RedirectResponse
    {
        try {
            $sale = $this->saleService->recordSale(
                $request->validated(),
                $request->user()
            );

            flash_message(
                "Sale of {$sale->quantity} units of '{$sale->product->name}' successfully recorded (#{$sale->sale_number}).",
                'success'
            );

            return redirect()->route('sales.index');
        } catch (InsufficientClientStockException $e) {
            flash_message($e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display client sales reports with visual charts.
     */
    public function reports(Request $request): View
    {
        $user = $request->user();
        $filters = [
            'client_id' => $user->id,
            'product_id' => $request->get('product_id', 'all'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $summary = $this->saleService->getClientSummary($user);
        $reportData = $this->saleService->getSalesReport($filters);
        $assignedProducts = $this->saleService->getClientInventoryBreakdown($user);

        return view('sales.reports', [
            'summary' => $summary,
            'reportData' => $reportData,
            'assignedProducts' => $assignedProducts,
            'filters' => $filters,
        ]);
    }
}
