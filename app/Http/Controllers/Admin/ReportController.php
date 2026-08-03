<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ClientRepositoryInterface;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected ClientRepositoryInterface $clientRepository
    ) {}

    /**
     * Display Master Financial & Inventory Analytics Dashboard.
     */
    public function index(Request $request): View
    {
        $filters = [
            'client_id' => $request->get('client_id', 'all'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $analytics = $this->reportService->getDashboardAnalytics(null, $filters);
        $clients = $this->clientRepository->getAllApproved();

        return view('admin.reports.index', [
            'summary' => $analytics['summary'],
            'monthlySales' => $analytics['monthly_sales'],
            'monthlyPayments' => $analytics['monthly_payments'],
            'topClients' => $analytics['top_clients'],
            'inventoryBreakdown' => $analytics['inventory_breakdown'],
            'filters' => $filters,
            'clients' => $clients,
        ]);
    }

    /**
     * Export Master Report as CSV stream.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $filters = [
            'client_id' => $request->get('client_id', 'all'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        return $this->reportService->exportCsv(null, $filters);
    }

    /**
     * Export Master Report as Excel stream.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $filters = [
            'client_id' => $request->get('client_id', 'all'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        return $this->reportService->exportExcel(null, $filters);
    }

    /**
     * Render Printable PDF view layout.
     */
    public function exportPdf(Request $request): View
    {
        $filters = [
            'client_id' => $request->get('client_id', 'all'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $analytics = $this->reportService->getDashboardAnalytics(null, $filters);

        return view('admin.reports.pdf', [
            'summary' => $analytics['summary'],
            'topClients' => $analytics['top_clients'],
            'inventoryBreakdown' => $analytics['inventory_breakdown'],
            'filters' => $filters,
        ]);
    }
}
