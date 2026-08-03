<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Global Client Sales History</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Monitor retail product sales recorded across all Ceylon AG partner clients</p>
            </div>
            <a href="{{ route('admin.sales.reports') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Master Sales Reports
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Search & Filter Bar -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.sales.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="search" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Sale #, Client, Product..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                </div>

                <div>
                    <label for="client_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Client</label>
                    <select id="client_id" name="client_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                        <option value="all" {{ ($filters['client_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ ($filters['client_id'] ?? '') == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->business_name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="product_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Product</label>
                    <select id="product_id" name="product_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                        <option value="all" {{ ($filters['product_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Products</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ ($filters['product_id'] ?? '') == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">From Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-xs font-semibold bg-emerald-600 text-white text-center">
                        Filter
                    </button>
                    <a href="{{ route('admin.sales.index') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Global Sales Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Sale Ref #</th>
                            <th class="py-3.5 px-6">Client Partner</th>
                            <th class="py-3.5 px-6">Product Details</th>
                            <th class="py-3.5 px-6">Qty Sold</th>
                            <th class="py-3.5 px-6">Unit Price</th>
                            <th class="py-3.5 px-6">Total Amount</th>
                            <th class="py-3.5 px-6">Buyer Name</th>
                            <th class="py-3.5 px-6">Selling Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-4 px-6 font-mono font-bold text-emerald-600 dark:text-emerald-400 text-[11px]">
                                    {{ $sale->sale_number }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $sale->client->name ?? 'N/A' }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $sale->client->business_name ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $sale->product->name ?? 'Deleted Product' }}</div>
                                    <div class="text-[11px] font-mono text-slate-400">SKU: {{ $sale->product->sku ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">
                                        {{ $sale->quantity }} units
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-700 dark:text-slate-300">
                                    LKR {{ number_format($sale->unit_price, 2) }}
                                </td>
                                <td class="py-4 px-6 font-bold text-emerald-600 dark:text-emerald-400">
                                    LKR {{ number_format($sale->total_amount, 2) }}
                                </td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-400">
                                    {{ $sale->customer_name ?: 'General Retail Client' }}
                                </td>
                                <td class="py-4 px-6 text-slate-500 dark:text-slate-400 text-[11px]">
                                    {{ $sale->sold_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    No retail sales recorded by clients yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sales->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
