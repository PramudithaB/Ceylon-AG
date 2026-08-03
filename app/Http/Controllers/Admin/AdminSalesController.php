<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ClientRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Services\ClientSaleService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSalesController extends Controller
{
    public function __construct(
        protected ClientSaleService $saleService,
        protected ClientRepositoryInterface $clientRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display global client sales history.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'client_id' => $request->get('client_id', 'all'),
            'product_id' => $request->get('product_id', 'all'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $sales = $this->saleService->getAllSales($filters, 15);
        $clients = $this->clientRepository->getAllApproved();
        $products = $this->productRepository->getAllPaginated([], 100);

        return view('admin.sales.index', [
            'sales' => $sales,
            'filters' => $filters,
            'clients' => $clients,
            'products' => $products,
        ]);
    }

    /**
     * Display master sales reports with charts for Super Admin / Admin.
     */
    public function reports(Request $request): View
    {
        $filters = [
            'client_id' => $request->get('client_id', 'all'),
            'product_id' => $request->get('product_id', 'all'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $reportData = $this->saleService->getSalesReport($filters);
        $clients = $this->clientRepository->getAllApproved();
        $products = $this->productRepository->getAllPaginated([], 100);

        return view('admin.sales.reports', [
            'reportData' => $reportData,
            'filters' => $filters,
            'clients' => $clients,
            'products' => $products,
        ]);
    }
}
