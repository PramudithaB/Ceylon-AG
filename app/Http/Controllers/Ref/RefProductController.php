<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class RefProductController extends Controller
{
    /**
     * Display assigned products and product availability status.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'in_stock') {
                $query->where('stock', '>', 5);
            } elseif ($request->availability === 'low_stock') {
                $query->whereBetween('stock', [1, 5]);
            } elseif ($request->availability === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('ref.products.index', compact('products', 'categories'));
    }
}
