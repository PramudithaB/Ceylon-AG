<?php

namespace App\Repositories;

use App\Models\ClientSale;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClientSaleRepository implements ClientSaleRepositoryInterface
{
    /**
     * Get paginated sales for a specific client.
     */
    public function getForClientPaginated(User $client, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = ClientSale::with(['product.category'])
            ->where('client_id', $client->id)
            ->latest('sold_at');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        if (! empty($filters['product_id']) && $filters['product_id'] !== 'all') {
            $query->where('product_id', $filters['product_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sold_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sold_at', '<=', $filters['end_date']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get all sales paginated (for Admin).
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = ClientSale::with(['client', 'product.category'])->latest('sold_at');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        if (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $query->where('client_id', $filters['client_id']);
        }

        if (! empty($filters['product_id']) && $filters['product_id'] !== 'all') {
            $query->where('product_id', $filters['product_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sold_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sold_at', '<=', $filters['end_date']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create client sale record.
     */
    public function create(array $data): ClientSale
    {
        return ClientSale::create($data);
    }

    /**
     * Get Client Inventory Summary Metrics (Total Assigned, Total Sold, Remaining Stock).
     */
    public function getClientSummary(User $client): array
    {
        $totalAssigned = ProductAssignment::where('client_id', $client->id)->sum('quantity');
        $totalSold = ClientSale::where('client_id', $client->id)->sum('quantity');
        $remainingStock = max(0, $totalAssigned - $totalSold);
        $totalRevenue = ClientSale::where('client_id', $client->id)->sum('total_amount');

        return [
            'total_assigned' => (int) $totalAssigned,
            'total_sold' => (int) $totalSold,
            'remaining_stock' => (int) $remainingStock,
            'total_revenue' => (float) $totalRevenue,
        ];
    }

    /**
     * Get per-product breakdown for Client Dashboard & Charts (Assigned vs Sold vs Remaining).
     */
    public function getClientInventoryBreakdown(User $client): Collection
    {
        // Get all unique product IDs assigned to this client
        $assignedProductIds = ProductAssignment::where('client_id', $client->id)
            ->pluck('product_id')
            ->unique();

        $products = Product::whereIn('id', $assignedProductIds)->with('category')->get();

        return $products->map(function ($product) use ($client) {
            $assignedQty = ProductAssignment::where('client_id', $client->id)
                ->where('product_id', $product->id)
                ->sum('quantity');

            $soldQty = ClientSale::where('client_id', $client->id)
                ->where('product_id', $product->id)
                ->sum('quantity');

            $remaining = max(0, $assignedQty - $soldQty);

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category->name ?? 'General',
                'image_url' => $product->image_url,
                'selling_price' => (float) $product->selling_price,
                'assigned_qty' => (int) $assignedQty,
                'sold_qty' => (int) $soldQty,
                'remaining_qty' => (int) $remaining,
                'revenue' => ClientSale::where('client_id', $client->id)->where('product_id', $product->id)->sum('total_amount'),
            ];
        });
    }

    /**
     * Aggregated sales report data for charts and reporting tables.
     */
    public function getSalesReportData(array $filters = []): array
    {
        $query = ClientSale::query();

        if (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $query->where('client_id', $filters['client_id']);
        }

        if (! empty($filters['product_id']) && $filters['product_id'] !== 'all') {
            $query->where('product_id', $filters['product_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sold_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sold_at', '<=', $filters['end_date']);
        }

        // Daily / Monthly Trend
        $salesTrend = (clone $query)
            ->select(DB::raw('DATE(sold_at) as date'), DB::raw('SUM(quantity) as units_sold'), DB::raw('SUM(total_amount) as total_revenue'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Top Selling Products
        $topProducts = (clone $query)
            ->select('product_id', DB::raw('SUM(quantity) as units_sold'), DB::raw('SUM(total_amount) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('units_sold', 'desc')
            ->take(5)
            ->get();

        return [
            'sales_trend' => $salesTrend,
            'top_products' => $topProducts,
            'total_units' => (int) (clone $query)->sum('quantity'),
            'total_revenue' => (float) (clone $query)->sum('total_amount'),
            'total_transactions' => (int) (clone $query)->count(),
        ];
    }
}
