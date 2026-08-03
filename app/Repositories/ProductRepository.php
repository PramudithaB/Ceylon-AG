<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get paginated products with search & filter options.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::with('category')->latest();

        // Search (Name, SKU, Description, Category Name)
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category Filter
        if (! empty($filters['category_id']) && $filters['category_id'] !== 'all') {
            $query->where('category_id', $filters['category_id']);
        }

        // Status Filter
        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // Stock Filter (low_stock, out_of_stock, in_stock)
        if (! empty($filters['stock_filter']) && $filters['stock_filter'] !== 'all') {
            if ($filters['stock_filter'] === 'low_stock') {
                $query->whereColumn('stock_quantity', '<=', 'minimum_stock')
                      ->where('stock_quantity', '>', 0);
            } elseif ($filters['stock_filter'] === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($filters['stock_filter'] === 'in_stock') {
                $query->whereColumn('stock_quantity', '>', 'minimum_stock');
            }
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Find product by ID.
     */
    public function findById(int|string $id): ?Product
    {
        return Product::with('category')->find($id);
    }

    /**
     * Create product.
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update product.
     */
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    /**
     * Update product stock quantity.
     */
    public function updateStock(Product $product, int $quantity): bool
    {
        return $product->update(['stock_quantity' => $quantity]);
    }

    /**
     * Delete product.
     */
    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    /**
     * Get summary counts.
     */
    public function getCounts(): array
    {
        return [
            'all' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
            'low_stock' => Product::whereColumn('stock_quantity', '<=', 'minimum_stock')->where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
        ];
    }
}
