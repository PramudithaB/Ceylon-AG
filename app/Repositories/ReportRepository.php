<?php

namespace App\Repositories;

use App\Models\ClientSale;
use App\Models\Payment;
use App\Models\ProductAssignment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportRepository implements ReportRepositoryInterface
{
    public function getSummaryMetrics(?User $client = null, array $filters = []): array
    {
        // 1. Total Sales
        $salesQuery = ClientSale::query();
        if ($client) {
            $salesQuery->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $salesQuery->where('client_id', $filters['client_id']);
        }
        if (! empty($filters['start_date'])) {
            $salesQuery->whereDate('sold_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $salesQuery->whereDate('sold_at', '<=', $filters['end_date']);
        }
        $totalSales = (float) $salesQuery->sum('total_amount');

        // 2. Total Approved Payments
        $paymentsQuery = Payment::where('status', Payment::STATUS_APPROVED);
        if ($client) {
            $paymentsQuery->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $paymentsQuery->where('client_id', $filters['client_id']);
        }
        if (! empty($filters['start_date'])) {
            $paymentsQuery->whereDate('payment_date', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $paymentsQuery->whereDate('payment_date', '<=', $filters['end_date']);
        }
        $totalPayments = (float) $paymentsQuery->sum('amount');

        // 3. Remaining Stock
        $assignedQuery = ProductAssignment::query();
        if ($client) {
            $assignedQuery->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $assignedQuery->where('client_id', $filters['client_id']);
        }
        $totalAssigned = (int) $assignedQuery->sum('quantity');

        $soldQtyQuery = ClientSale::query();
        if ($client) {
            $soldQtyQuery->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $soldQtyQuery->where('client_id', $filters['client_id']);
        }
        $totalSoldUnits = (int) $soldQtyQuery->sum('quantity');

        $remainingStock = max(0, $totalAssigned - $totalSoldUnits);
        $outstandingBalance = max(0, $totalSales - $totalPayments);

        return [
            'total_sales' => $totalSales,
            'total_payments' => $totalPayments,
            'remaining_stock' => $remainingStock,
            'outstanding_balance' => $outstandingBalance,
            'total_assigned_units' => $totalAssigned,
            'total_sold_units' => $totalSoldUnits,
        ];
    }

    public function getMonthlySalesTrend(?User $client = null, array $filters = []): array
    {
        $query = ClientSale::select(
            DB::raw("DATE_FORMAT(sold_at, '%Y-%m') as month"),
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('SUM(quantity) as units_sold')
        );

        if ($client) {
            $query->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $query->where('client_id', $filters['client_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sold_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $query->whereDate('sold_at', '<=', $filters['end_date']);
        }

        return $query->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->toArray();
    }

    public function getMonthlyPaymentsTrend(?User $client = null, array $filters = []): array
    {
        $query = Payment::select(
            DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as month"),
            DB::raw('SUM(amount) as total_payments')
        )->where('status', Payment::STATUS_APPROVED);

        if ($client) {
            $query->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $query->where('client_id', $filters['client_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('payment_date', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $query->whereDate('payment_date', '<=', $filters['end_date']);
        }

        return $query->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->toArray();
    }

    public function getTopClients(int $limit = 5, array $filters = []): array
    {
        $query = ClientSale::select(
            'client_id',
            DB::raw('SUM(total_amount) as total_revenue'),
            DB::raw('SUM(quantity) as total_units')
        )->with('client');

        if (! empty($filters['start_date'])) {
            $query->whereDate('sold_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $query->whereDate('sold_at', '<=', $filters['end_date']);
        }

        return $query->groupBy('client_id')
            ->orderByDesc('total_revenue')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'client_id' => $item->client_id,
                    'name' => $item->client->name ?? 'Unknown',
                    'business_name' => $item->client->business_name ?? '-',
                    'total_revenue' => (float) $item->total_revenue,
                    'total_units' => (int) $item->total_units,
                ];
            })
            ->toArray();
    }

    public function getInventoryBreakdown(?User $client = null, array $filters = []): array
    {
        $assignmentsQuery = ProductAssignment::with('product');
        if ($client) {
            $assignmentsQuery->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $assignmentsQuery->where('client_id', $filters['client_id']);
        }

        $assignments = $assignmentsQuery->get()->groupBy('product_id');

        $result = [];
        foreach ($assignments as $productId => $group) {
            $product = $group->first()->product;
            $assignedQty = $group->sum('quantity');

            $soldQtyQuery = ClientSale::where('product_id', $productId);
            if ($client) {
                $soldQtyQuery->where('client_id', $client->id);
            } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
                $soldQtyQuery->where('client_id', $filters['client_id']);
            }
            $soldQty = (int) $soldQtyQuery->sum('quantity');

            $result[] = [
                'product_id' => $productId,
                'name' => $product->name ?? 'Deleted Product',
                'sku' => $product->sku ?? 'N/A',
                'assigned_qty' => $assignedQty,
                'sold_qty' => $soldQty,
                'remaining_qty' => max(0, $assignedQty - $soldQty),
            ];
        }

        return $result;
    }

    public function getDetailedReportData(?User $client = null, array $filters = []): array
    {
        $salesQuery = ClientSale::with(['client', 'product']);
        if ($client) {
            $salesQuery->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $salesQuery->where('client_id', $filters['client_id']);
        }
        if (! empty($filters['start_date'])) {
            $salesQuery->whereDate('sold_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $salesQuery->whereDate('sold_at', '<=', $filters['end_date']);
        }
        $sales = $salesQuery->latest('sold_at')->get();

        $paymentsQuery = Payment::with('client');
        if ($client) {
            $paymentsQuery->where('client_id', $client->id);
        } elseif (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $paymentsQuery->where('client_id', $filters['client_id']);
        }
        if (! empty($filters['start_date'])) {
            $paymentsQuery->whereDate('payment_date', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $paymentsQuery->whereDate('payment_date', '<=', $filters['end_date']);
        }
        $payments = $paymentsQuery->latest('payment_date')->get();

        return [
            'sales' => $sales,
            'payments' => $payments,
        ];
    }
}
