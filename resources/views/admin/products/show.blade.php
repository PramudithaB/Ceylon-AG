<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Product Profile Details</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">View pricing breakdown, stock health, profit margins, and manage inventory</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-amber-500 hover:bg-amber-400 text-white shadow-md transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Product
                </a>

                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Permanently delete this product from catalog?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-500 text-white shadow-md transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Hero Product Banner Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                <!-- Image -->
                <img class="w-36 h-36 rounded-2xl object-cover border-2 border-emerald-500 shadow-md" src="{{ $product->image_url }}" alt="{{ $product->name }}">

                <div class="flex-1 text-center md:text-left space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 mb-1">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ $product->name }}</h2>
                            <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5">SKU: {{ $product->sku }}</p>
                        </div>

                        <!-- Stock Status Pill -->
                        <div>
                            @if($product->isOutOfStock())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span>
                                    Out of Stock
                                </span>
                            @elseif($product->isLowStock())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                                    Low Stock Alert
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                                    In Stock
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($product->description)
                        <p class="text-xs text-slate-600 dark:text-slate-300 pt-2 border-t border-slate-100 dark:border-slate-800">
                            {{ $product->description }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pricing Breakdown -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pricing Structure</h3>
                </div>

                <dl class="space-y-3 text-xs">
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Buying Price (Cost):</dt>
                        <dd class="font-bold text-slate-800 dark:text-slate-200">LKR {{ number_format($product->buying_price, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Dealer Price:</dt>
                        <dd class="font-bold text-emerald-600 dark:text-emerald-400">LKR {{ number_format($product->dealer_price, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-800">
                        <dt class="text-slate-700 dark:text-slate-300 font-bold">Standard Selling Price:</dt>
                        <dd class="font-black text-slate-900 dark:text-white text-sm">LKR {{ number_format($product->selling_price, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Profit Margins -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Calculated Profit Margins</h3>
                </div>

                <dl class="space-y-3 text-xs">
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Retail Margin per unit:</dt>
                        <dd class="font-bold text-emerald-600 dark:text-emerald-400">+LKR {{ number_format($product->profit_margin, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-slate-500 dark:text-slate-400">Dealer Margin per unit:</dt>
                        <dd class="font-bold text-teal-600 dark:text-teal-400">+LKR {{ number_format($product->dealer_price - $product->buying_price, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Stock Control & Adjustment Form -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Stock Inventory Control</h3>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <div>
                        <div class="text-slate-400">Current Stock:</div>
                        <div class="text-xl font-black text-slate-900 dark:text-white">{{ $product->stock_quantity }} units</div>
                    </div>
                    <div class="text-right">
                        <div class="text-slate-400">Low Stock Limit:</div>
                        <div class="font-bold text-slate-700 dark:text-slate-300">{{ $product->minimum_stock }} units</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.products.adjust-stock', $product) }}" class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    @csrf
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Quick Stock Adjustment</label>
                    <div class="flex space-x-2">
                        <input type="number" name="quantity" min="1" required value="10" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-1.5 px-3">
                        <button type="submit" name="type" value="add" class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-500 transition-colors">
                            +Add
                        </button>
                        <button type="submit" name="type" value="set" class="px-3 py-1.5 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-700 transition-colors">
                            Set
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
