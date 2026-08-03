<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findById(int|string $id): ?Product;
    public function create(array $data): Product;
    public function update(Product $product, array $data): bool;
    public function updateStock(Product $product, int $quantity): bool;
    public function delete(Product $product): bool;
    public function getCounts(): array;
}
