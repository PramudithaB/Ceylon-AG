<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    /**
     * Display client partner report dashboard.
     */
    public function index(Request $request): View
    {
        $client = $request->user();
        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $analytics = $this->reportService->getDashboardAnalytics($client, $filters);

        return view('reports.index', [
            'summary' => $analytics['summary'],
            'monthlySales' => $analytics['monthly_sales'],
            'monthlyPayments' => $analytics['monthly_payments'],
            'inventoryBreakdown' => $analytics['inventory_breakdown'],
            'filters' => $filters,
        ]);
    }

    /**
     * Export client report as CSV stream.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $client = $request->user();
        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        return $this->reportService->exportCsv($client, $filters);
    }

    /**
     * Export client report as Excel stream.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $client = $request->user();
        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        return $this->reportService->exportExcel($client, $filters);
    }

    /**
     * Render Printable PDF view layout for client.
     */
    public function exportPdf(Request $request): View
    {
        $client = $request->user();
        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $analytics = $this->reportService->getDashboardAnalytics($client, $filters);

        return view('reports.pdf', [
            'summary' => $analytics['summary'],
            'inventoryBreakdown' => $analytics['inventory_breakdown'],
            'filters' => $filters,
            'client' => $client,
        ]);
    }
}
