<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllActive(): Collection
    {
        return Category::where('is_active', true)->orderBy('name')->get();
    }

    public function getAll(): Collection
    {
        return Category::withCount('products')->orderBy('name')->get();
    }

    public function findById(int|string $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}
