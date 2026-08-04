<x-ref-layout>
    <x-slot name="header">
        Products Catalog & Availability
    </x-slot>

    <!-- Filter Bar -->
    <div class="bg-slate-900/90 border border-slate-800 p-5 rounded-2xl shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Assigned Products & Availability</h2>
            <p class="text-xs text-slate-400 mt-0.5">Check inventory levels and stock status across product lines</p>
        </div>

        <form method="GET" action="{{ route('ref.products.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product name or SKU..." class="px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none">
            
            <select name="availability" class="px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                <option value="">All Stock Status</option>
                <option value="in_stock" {{ request('availability') == 'in_stock' ? 'selected' : '' }}>In Stock (> 5)</option>
                <option value="low_stock" {{ request('availability') == 'low_stock' ? 'selected' : '' }}>Low Stock (1 - 5)</option>
                <option value="out_of_stock" {{ request('availability') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-md shadow-emerald-600/20">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'availability', 'category']))
                <a href="{{ route('ref.products.index') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Clear</a>
            @endif
        </form>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($products as $product)
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl flex flex-col justify-between hover:border-slate-700 transition-all">
                <div>
                    <!-- Category Badge & Availability -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-0.5 bg-slate-800 text-slate-300 text-[10px] font-bold rounded-full uppercase tracking-wider">
                            {{ $product->category->name ?? 'General' }}
                        </span>

                        @if($product->stock > 5)
                            <span class="px-2.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-[10px] rounded-full">
                                In Stock ({{ $product->stock }})
                            </span>
                        @elseif($product->stock > 0)
                            <span class="px-2.5 py-0.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold text-[10px] rounded-full">
                                Low Stock ({{ $product->stock }})
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold text-[10px] rounded-full">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <!-- Product Name & Image -->
                    <div class="flex items-center gap-3 mb-3">
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-800">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center text-slate-500 font-bold text-lg">
                                {{ strtoupper(substr($product->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-white text-sm line-clamp-1">{{ $product->name }}</h3>
                            <p class="text-[11px] text-slate-400">SKU: {{ $product->sku }}</p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-400 line-clamp-2 mb-4">{{ $product->description ?? 'High quality agrochemical product line.' }}</p>
                </div>

                <!-- Pricing & Action -->
                <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-500 block uppercase">Dealer Price</span>
                        <span class="text-sm font-extrabold text-emerald-400">LKR {{ number_format($product->dealer_price ?? $product->price, 2) }}</span>
                    </div>

                    <a href="{{ route('ref.stock-requests.create') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-emerald-600 text-slate-200 hover:text-white font-semibold text-xs rounded-lg transition-colors">
                        Request Stock
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-slate-900/90 border border-slate-800 rounded-2xl p-10 text-center text-slate-500">
                No products found in catalog.
            </div>
        @endforelse
    </div>

    <div class="p-4 bg-slate-900/90 border border-slate-800 rounded-2xl">
        {{ $products->links() }}
    </div>
</x-ref-layout>
