<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getAllActive(): Collection;
    public function getAll(): Collection;
    public function findById(int|string $id): ?Category;
    public function create(array $data): Category;
    public function update(Category $category, array $data): bool;
    public function delete(Category $category): bool;
}
