<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Product Catalog Management</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Manage agricultural products, track stock inventory levels, pricing, and categories</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Manage Categories
                </a>
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Product
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Search & Filter Bar -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Search Products</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by Product Name, SKU, Description..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 pl-9 pr-3 py-2 text-slate-800 dark:text-slate-200">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Category</label>
                    <select id="category_id" name="category_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 py-2 px-3 text-slate-800 dark:text-slate-200">
                        <option value="all" {{ ($filters['category_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Status</label>
                    <select id="status" name="status" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 py-2 px-3 text-slate-800 dark:text-slate-200">
                        <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>

                <!-- Submit / Reset -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-xs font-semibold bg-slate-900 hover:bg-slate-800 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white transition-all text-center">
                        Filter
                    </button>
                    @if(!empty($filters['search']) || ($filters['category_id'] ?? 'all') !== 'all' || ($filters['status'] ?? 'all') !== 'all' || ($filters['stock_filter'] ?? 'all') !== 'all')
                        <a href="{{ route('admin.products.index') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Stock Health Quick Filter Tabs -->
        <div class="flex items-center space-x-2 border-b border-slate-200 dark:border-slate-800 pb-3 overflow-x-auto">
            <a href="{{ route('admin.products.index', array_merge($filters, ['stock_filter' => 'all'])) }}" 
               class="flex items-center space-x-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($filters['stock_filter'] ?? 'all') === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <span>All Items</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-slate-700/20 text-current">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('admin.products.index', array_merge($filters, ['stock_filter' => 'low_stock'])) }}" 
               class="flex items-center space-x-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($filters['stock_filter'] ?? '') === 'low_stock' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40' }}">
                <span>Low Stock Alert</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-current">{{ $counts['low_stock'] }}</span>
            </a>
            <a href="{{ route('admin.products.index', array_merge($filters, ['stock_filter' => 'out_of_stock'])) }}" 
               class="flex items-center space-x-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($filters['stock_filter'] ?? '') === 'out_of_stock' ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20' : 'text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40' }}">
                <span>Out of Stock</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-current">{{ $counts['out_of_stock'] }}</span>
            </a>
        </div>

        <!-- Products Table Container -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Product Details</th>
                            <th class="py-3.5 px-6">Category</th>
                            <th class="py-3.5 px-6">Prices (Buying / Dealer / Selling)</th>
                            <th class="py-3.5 px-6">Stock Health</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($products as $product)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- Product Details -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-12 h-12 rounded-xl object-cover border border-slate-200 dark:border-slate-800 shadow-sm" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                        <div>
                                            <a href="{{ route('admin.products.show', $product) }}" class="font-bold text-slate-900 dark:text-white hover:text-emerald-500 transition-colors">
                                                {{ $product->name }}
                                            </a>
                                            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>

                                <!-- Prices -->
                                <td class="py-4 px-6">
                                    <div class="space-y-0.5 text-[11px]">
                                        <div class="text-slate-500">Buying: <span class="font-semibold text-slate-700 dark:text-slate-300">LKR {{ number_format($product->buying_price, 2) }}</span></div>
                                        <div class="text-slate-500">Dealer: <span class="font-semibold text-emerald-600 dark:text-emerald-400">LKR {{ number_format($product->dealer_price, 2) }}</span></div>
                                        <div class="text-slate-500">Selling: <span class="font-bold text-slate-900 dark:text-white">LKR {{ number_format($product->selling_price, 2) }}</span></div>
                                    </div>
                                </td>

                                <!-- Stock Health & Quick Adjust -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-2">
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white text-sm">
                                                {{ $product->stock_quantity }} units
                                            </div>
                                            <div class="text-[10px] text-slate-400">Min Threshold: {{ $product->minimum_stock }}</div>
                                        </div>
                                    </div>

                                    <!-- Quick Stock Update Form -->
                                    <form method="POST" action="{{ route('admin.products.adjust-stock', $product) }}" class="flex items-center space-x-1 mt-2">
                                        @csrf
                                        <input type="number" name="quantity" min="1" value="5" class="w-16 text-[10px] py-1 px-1.5 rounded-lg border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                                        <button type="submit" name="type" value="add" title="Add Stock" class="p-1 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 hover:bg-emerald-200 text-[10px] font-bold">
                                            +Add
                                        </button>
                                        <button type="submit" name="type" value="subtract" title="Subtract Stock" class="p-1 rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300 hover:bg-rose-200 text-[10px] font-bold">
                                            -Sub
                                        </button>
                                    </form>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    @if($product->isOutOfStock())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Out of Stock
                                        </span>
                                    @elseif($product->isLowStock())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                            Low Stock Alert
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            In Stock
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.products.show', $product) }}" title="View Product Details" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.products.edit', $product) }}" title="Edit Product" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product permanently?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Delete Product" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    No products found matching your search criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
