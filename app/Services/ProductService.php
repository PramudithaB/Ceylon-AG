<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Get paginated products with search & filter.
     */
    public function getProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->getAllPaginated($filters, $perPage);
    }

    /**
     * Get product counts by category / status / stock level.
     */
    public function getProductCounts(): array
    {
        return $this->productRepository->getCounts();
    }

    /**
     * Find product by ID.
     */
    public function getProduct(int|string $id): ?Product
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Create product with optional image upload.
     */
    public function createProduct(array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $data['image_path'] = $image->store('products', 'public');
        }

        return $this->productRepository->create($data);
    }

    /**
     * Update product details and optional image update.
     */
    public function updateProduct(Product $product, array $data, ?UploadedFile $image = null): bool
    {
        if ($image) {
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $data['image_path'] = $image->store('products', 'public');
        }

        return $this->productRepository->update($product, $data);
    }

    /**
     * Update product stock quantity directly.
     */
    public function updateStock(Product $product, int $quantity): bool
    {
        return $this->productRepository->updateStock($product, $quantity);
    }

    /**
     * Adjust stock quantity by addition or subtraction.
     */
    public function adjustStock(Product $product, int $quantity, string $type = 'add'): bool
    {
        $newStock = $type === 'add'
            ? $product->stock_quantity + $quantity
            : max(0, $product->stock_quantity - $quantity);

        return $this->productRepository->updateStock($product, $newStock);
    }

    /**
     * Delete product and clean up image file.
     */
    public function deleteProduct(Product $product): bool
    {
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        return $this->productRepository->delete($product);
    }
}
