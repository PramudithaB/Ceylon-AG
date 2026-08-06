<x-ref-layout>
    <x-slot name="header">
        Products Catalog & Availability
    </x-slot>

    <!-- Header & Filter Bar -->
    <div class="ref-card rounded-3xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Assigned Products & Availability</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Check inventory levels and stock status across product lines</p>
        </div>

        <form method="GET" action="{{ route('ref.products.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search product name or SKU..." 
                    class="auth-input pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white"
                >
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            
            <select name="availability" class="auth-input px-4 py-2.5 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                <option value="">All Stock Status</option>
                <option value="in_stock" {{ request('availability') == 'in_stock' ? 'selected' : '' }}>In Stock (> 5)</option>
                <option value="low_stock" {{ request('availability') == 'low_stock' ? 'selected' : '' }}>Low Stock (1 - 5)</option>
                <option value="out_of_stock" {{ request('availability') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
            </select>

            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-md hover:opacity-95 transition-all">
                Filter
            </button>
            
            @if(request()->anyFilled(['search', 'availability', 'category']))
                <a href="{{ route('ref.products.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-2xl transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="ref-card rounded-3xl p-6 flex flex-col justify-between group hover:-translate-y-1 transition-all">
                <div>
                    <!-- Category Badge & Availability Pill -->
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-[10px] font-extrabold rounded-full uppercase tracking-wider">
                            {{ $product->category->name ?? 'General' }}
                        </span>

                        @if($product->stock > 5)
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-[#1E8E3E] font-extrabold text-[10px] rounded-full">
                                In Stock ({{ $product->stock }})
                            </span>
                        @elseif($product->stock > 0)
                            <span class="px-3 py-1 bg-amber-50 border border-amber-200 text-amber-700 font-extrabold text-[10px] rounded-full">
                                Low Stock ({{ $product->stock }})
                            </span>
                        @else
                            <span class="px-3 py-1 bg-rose-50 border border-rose-200 text-rose-600 font-extrabold text-[10px] rounded-full">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <!-- Product Image & Name -->
                    <div class="flex items-center gap-4 mb-4">
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-100 shadow-sm">
                        @else
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-emerald-700/20">
                                {{ strtoupper(substr($product->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-sm line-clamp-1 group-hover:text-[#1E8E3E] transition-colors">{{ $product->name }}</h3>
                            <p class="text-[11px] text-gray-400 font-bold mt-0.5">SKU: {{ $product->sku }}</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 font-medium line-clamp-2 mb-4 leading-relaxed">{{ $product->description ?? 'High performance agricultural chemical solution.' }}</p>
                </div>

                <!-- Price & Action CTA -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Dealer Price</span>
                        <span class="text-base font-extrabold text-[#1E8E3E]">LKR {{ number_format($product->dealer_price ?? $product->price, 2) }}</span>
                    </div>

                    <a href="{{ route('ref.stock-requests.create') }}" class="px-4 py-2 bg-emerald-50 hover:bg-[#1E8E3E] text-[#1E8E3E] hover:text-white font-extrabold text-xs rounded-xl transition-all shadow-sm">
                        Request Stock
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full ref-card rounded-3xl p-12 text-center text-gray-400 font-medium">
                No products found matching your filter criteria.
            </div>
        @endforelse
    </div>

    <div class="ref-card rounded-3xl p-4">
        {{ $products->links() }}
    </div>
</x-ref-layout>
