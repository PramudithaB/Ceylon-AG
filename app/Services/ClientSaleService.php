<?php

namespace App\Services;

use App\Exceptions\InsufficientClientStockException;
use App\Models\ClientSale;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\User;
use App\Repositories\ClientSaleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClientSaleService
{
    public function __construct(
        protected ClientSaleRepositoryInterface $saleRepository
    ) {}

    /**
     * Get paginated sales for client.
     */
    public function getClientSales(User $client, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->saleRepository->getForClientPaginated($client, $filters, $perPage);
    }

    /**
     * Get all sales for admin.
     */
    public function getAllSales(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->saleRepository->getAllPaginated($filters, $perPage);
    }

    /**
     * Record a new retail product sale by client.
     *
     * @throws InsufficientClientStockException
     */
    public function recordSale(array $data, User $client): ClientSale
    {
        return DB::transaction(function () use ($data, $client) {
            $productId = $data['product_id'];
            $quantity = (int) $data['quantity'];

            $product = Product::findOrFail($productId);

            // Calculate client's total assigned units for this product
            $totalAssigned = ProductAssignment::where('client_id', $client->id)
                ->where('product_id', $productId)
                ->sum('quantity');

            // Calculate client's total sold units for this product
            $totalSold = ClientSale::where('client_id', $client->id)
                ->where('product_id', $productId)
                ->sum('quantity');

            $remainingStock = $totalAssigned - $totalSold;

            if ($quantity > $remainingStock) {
                throw new InsufficientClientStockException(
                    "Cannot record sale of {$quantity} units. You only have {$remainingStock} assigned units remaining in your inventory."
                );
            }

            // Determine unit selling price (from product setting or default)
            $unitPrice = (float) ($product->selling_price ?? 0);
            $totalAmount = $quantity * $unitPrice;

            return $this->saleRepository->create([
                'sale_number' => ClientSale::generateSaleNumber(),
                'client_id' => $client->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'customer_name' => $data['customer_name'] ?? null,
                'notes' => $data['notes'] ?? null,
                'sold_at' => $data['sold_at'],
            ]);
        });
    }

    /**
     * Get client summary numbers (Total Assigned, Total Sold, Remaining Stock, Revenue).
     */
    public function getClientSummary(User $client): array
    {
        return $this->saleRepository->getClientSummary($client);
    }

    /**
     * Get per-product breakdown for Client Dashboard and Chart.js integration.
     */
    public function getClientInventoryBreakdown(User $client): Collection
    {
        return $this->saleRepository->getClientInventoryBreakdown($client);
    }

    /**
     * Get aggregated sales report metrics for charts and exports.
     */
    public function getSalesReport(array $filters = []): array
    {
        return $this->saleRepository->getSalesReportData($filters);
    }
}
