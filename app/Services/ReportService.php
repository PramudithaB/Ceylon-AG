<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ReportRepositoryInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportService
{
    public function __construct(
        protected ReportRepositoryInterface $reportRepository
    ) {}

    public function getDashboardAnalytics(?User $client = null, array $filters = []): array
    {
        return [
            'summary' => $this->reportRepository->getSummaryMetrics($client, $filters),
            'monthly_sales' => $this->reportRepository->getMonthlySalesTrend($client, $filters),
            'monthly_payments' => $this->reportRepository->getMonthlyPaymentsTrend($client, $filters),
            'top_clients' => $this->reportRepository->getTopClients(5, $filters),
            'inventory_breakdown' => $this->reportRepository->getInventoryBreakdown($client, $filters),
        ];
    }

    /**
     * Stream CSV download response.
     */
    public function exportCsv(?User $client = null, array $filters = []): StreamedResponse
    {
        $filename = 'ceylonag_report_' . date('Ymd_His') . '.csv';
        $data = $this->reportRepository->getDetailedReportData($client, $filters);
        $summary = $this->reportRepository->getSummaryMetrics($client, $filters);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($data, $summary) {
            $handle = fopen('php://output', 'w');

            // Header Section: Summary
            fputcsv($handle, ['CEYLON AG FINANCIAL & INVENTORY MASTER REPORT']);
            fputcsv($handle, ['Generated At', date('Y-m-d H:i:s')]);
            fputcsv($handle, ['Total Sales (LKR)', number_format($summary['total_sales'], 2)]);
            fputcsv($handle, ['Total Payments (LKR)', number_format($summary['total_payments'], 2)]);
            fputcsv($handle, ['Remaining Stock (Units)', $summary['remaining_stock']]);
            fputcsv($handle, ['Outstanding Balance (LKR)', number_format($summary['outstanding_balance'], 2)]);
            fputcsv($handle, []);

            // Sales Section
            fputcsv($handle, ['RECORDED RETAIL SALES']);
            fputcsv($handle, ['Sale #', 'Client', 'Product', 'SKU', 'Quantity', 'Unit Price (LKR)', 'Total Amount (LKR)', 'Customer Name', 'Sold Date']);

            foreach ($data['sales'] as $sale) {
                fputcsv($handle, [
                    $sale->sale_number,
                    $sale->client->business_name ?? $sale->client->name ?? 'N/A',
                    $sale->product->name ?? 'N/A',
                    $sale->product->sku ?? 'N/A',
                    $sale->quantity,
                    number_format((float) $sale->unit_price, 2),
                    number_format((float) $sale->total_amount, 2),
                    $sale->customer_name ?: 'General Retail',
                    $sale->sold_at->format('Y-m-d'),
                ]);
            }

            fputcsv($handle, []);

            // Payments Section
            fputcsv($handle, ['SUBMITTED BANK PAYMENTS']);
            fputcsv($handle, ['Payment #', 'Client', 'Amount (LKR)', 'Payment Date', 'Bank Name', 'Reference #', 'Status']);

            foreach ($data['payments'] as $payment) {
                fputcsv($handle, [
                    $payment->payment_number,
                    $payment->client->business_name ?? $payment->client->name ?? 'N/A',
                    number_format((float) $payment->amount, 2),
                    $payment->payment_date->format('Y-m-d'),
                    $payment->bank_name,
                    $payment->reference_number,
                    strtoupper($payment->status),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Stream Excel-compatible CSV download response.
     */
    public function exportExcel(?User $client = null, array $filters = []): StreamedResponse
    {
        $filename = 'ceylonag_master_report_' . date('Ymd_His') . '.xlsx';
        $response = $this->exportCsv($client, $filters);
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');

        return $response;
    }
}
