<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Product Assignments History</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Track and manage inventory stock allocations assigned to registered Ceylon AG clients</p>
            </div>
            <a href="{{ route('admin.product-assignments.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Product Assignment
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Search & Filters -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.product-assignments.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Search Assignments</label>
                    <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Assignment #, Client, Product..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 py-2 px-3">
                </div>

                <!-- Client Filter -->
                <div>
                    <label for="client_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Client</label>
                    <select id="client_id" name="client_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 py-2 px-3">
                        <option value="all" {{ ($filters['client_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ ($filters['client_id'] ?? '') == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->business_name }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Filter -->
                <div>
                    <label for="product_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Product</label>
                    <select id="product_id" name="product_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 py-2 px-3">
                        <option value="all" {{ ($filters['product_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ ($filters['product_id'] ?? '') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-xs font-semibold bg-slate-900 dark:bg-emerald-600 text-white text-center">
                        Filter
                    </button>
                    @if(!empty($filters['search']) || ($filters['client_id'] ?? 'all') !== 'all' || ($filters['product_id'] ?? 'all') !== 'all')
                        <a href="{{ route('admin.product-assignments.index') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- History Table Container -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Ref #</th>
                            <th class="py-3.5 px-6">Client Name / Business</th>
                            <th class="py-3.5 px-6">Product Details</th>
                            <th class="py-3.5 px-6">Quantity</th>
                            <th class="py-3.5 px-6">Dealer Price</th>
                            <th class="py-3.5 px-6">Total Value</th>
                            <th class="py-3.5 px-6">Assigned Date</th>
                            <th class="py-3.5 px-6 text-right">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($assignments as $assignment)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-4 px-6 font-mono font-bold text-emerald-600 dark:text-emerald-400 text-[11px]">
                                    {{ $assignment->assignment_number }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $assignment->client->name ?? 'N/A' }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $assignment->client->business_name ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $assignment->product->name ?? 'Deleted Product' }}</div>
                                    <div class="text-[11px] font-mono text-slate-400">SKU: {{ $assignment->product->sku ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                        {{ $assignment->quantity }} units
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-700 dark:text-slate-300">
                                    LKR {{ number_format($assignment->dealer_price, 2) }}
                                </td>
                                <td class="py-4 px-6 font-bold text-emerald-600 dark:text-emerald-400">
                                    LKR {{ number_format($assignment->total_dealer_amount, 2) }}
                                </td>
                                <td class="py-4 px-6 text-slate-500 dark:text-slate-400 text-[11px]">
                                    {{ $assignment->assigned_at->format('M d, Y') }}
                                    <span class="block text-[10px] text-slate-400">{{ $assignment->assigned_at->format('h:i A') }}</span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('admin.product-assignments.show', $assignment) }}" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-emerald-500 transition-colors inline-block">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    No product assignments recorded yet. Allocate products to clients using the form.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($assignments->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
