<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\CategoryRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * Display a listing of products with search and filters.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'category_id' => $request->get('category_id', 'all'),
            'status' => $request->get('status', 'all'),
            'stock_filter' => $request->get('stock_filter', 'all'),
        ];

        $products = $this->productService->getProducts($filters, 12);
        $counts = $this->productService->getProductCounts();
        $categories = $this->categoryRepository->getAllActive();

        return view('admin.products.index', [
            'products' => $products,
            'filters' => $filters,
            'counts' => $counts,
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified product details.
     */
    public function show(Product $product): View
    {
        $product->load('category');

        return view('admin.products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = $this->categoryRepository->getAllActive();

        return view('admin.products.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created product in storage using StoreProductRequest.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $image = $request->file('image');

        $product = $this->productService->createProduct($validated, $image);

        flash_message("Product '{$product->name}' created successfully with SKU {$product->sku}.", 'success');

        return redirect()->route('admin.products.show', $product);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = $this->categoryRepository->getAllActive();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified product in storage using UpdateProductRequest.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        $image = $request->file('image');

        $this->productService->updateProduct($product, $validated, $image);

        flash_message("Product '{$product->name}' updated successfully.", 'success');

        return redirect()->route('admin.products.show', $product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $this->productService->deleteProduct($product);

        flash_message("Product '{$name}' deleted successfully.", 'warning');

        return redirect()->route('admin.products.index');
    }

    /**
     * Quick stock quantity adjustment.
     */
    public function adjustStock(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:add,subtract,set'],
        ]);

        $quantity = (int) $request->input('quantity');
        $type = $request->input('type');

        if ($type === 'set') {
            $this->productService->updateStock($product, $quantity);
        } else {
            $this->productService->adjustStock($product, $quantity, $type);
        }

        flash_message("Stock quantity for '{$product->name}' updated successfully.", 'success');

        return redirect()->back();
    }
}
