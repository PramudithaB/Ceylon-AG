<x-ref-layout>
    <x-slot name="header">
        Territory Sales Reports & Analytics
    </x-slot>

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ref.sales.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 font-semibold">
            &larr; Back to Sales History
        </a>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Sales Count</span>
            <div class="text-3xl font-extrabold text-white mt-2">{{ number_format($totalSalesCount) }}</div>
            <p class="text-xs text-slate-500 mt-1">Total completed transactions</p>
        </div>

        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Products Sold</span>
            <div class="text-3xl font-extrabold text-teal-400 mt-2">{{ number_format($totalItemsSold) }} units</div>
            <p class="text-xs text-slate-500 mt-1">Volume across all product lines</p>
        </div>

        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Generated Revenue</span>
            <div class="text-3xl font-extrabold text-emerald-400 mt-2">LKR {{ number_format($totalRevenue, 2) }}</div>
            <p class="text-xs text-slate-500 mt-1">Gross revenue generated</p>
        </div>
    </div>

    <!-- Breakdown Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl p-6 space-y-4">
        <h3 class="text-base font-bold text-white tracking-tight">Sales Breakdown by Client</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/70 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">Client</th>
                        <th class="px-5 py-3.5">Transactions</th>
                        <th class="px-5 py-3.5">Items Sold</th>
                        <th class="px-5 py-3.5">Revenue Contribution</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($salesByClient as $item)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4 font-bold text-white">{{ $item->client->business_name ?? $item->client->name }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-200">{{ number_format($item->sales_count) }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-200">{{ number_format($item->items) }} units</td>
                            <td class="px-5 py-4 font-extrabold text-emerald-400">LKR {{ number_format($item->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-500">
                                No client sales reports data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-ref-layout>
