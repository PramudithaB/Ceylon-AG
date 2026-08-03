<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = $this->categoryRepository->getAll();

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $category = $this->categoryRepository->create($validated);

        flash_message("Category '{$category->name}' created successfully!", 'success');

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            flash_message("Cannot delete category '{$category->name}' because it contains associated products.", 'error');
            return redirect()->back();
        }

        $name = $category->name;
        $this->categoryRepository->delete($category);

        flash_message("Category '{$name}' deleted successfully.", 'warning');

        return redirect()->route('admin.categories.index');
    }
}
