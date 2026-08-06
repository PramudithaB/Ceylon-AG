<x-ref-layout>
    <x-slot name="header">
        Territory Sales Reports & Analytics
    </x-slot>

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ref.sales.index') }}" class="text-xs font-bold text-gray-500 hover:text-[#1E8E3E] flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Sales History</span>
        </a>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="ref-card rounded-3xl p-6 sm:p-8">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Total Sales Count</span>
            <div class="text-3xl font-extrabold text-gray-900 tracking-tight mt-2">{{ number_format($totalSalesCount) }}</div>
            <p class="text-xs text-gray-400 font-medium mt-1">Total completed transactions</p>
        </div>

        <div class="ref-card rounded-3xl p-6 sm:p-8">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Total Products Sold</span>
            <div class="text-3xl font-extrabold text-[#1E8E3E] tracking-tight mt-2">{{ number_format($totalItemsSold) }} units</div>
            <p class="text-xs text-gray-400 font-medium mt-1">Volume across all product lines</p>
        </div>

        <div class="ref-card rounded-3xl p-6 sm:p-8">
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Total Generated Revenue</span>
            <div class="text-3xl font-extrabold text-[#1E8E3E] tracking-tight mt-2">LKR {{ number_format($totalRevenue, 2) }}</div>
            <p class="text-xs text-gray-400 font-medium mt-1">Gross territory revenue</p>
        </div>
    </div>

    <!-- Breakdown Table Card -->
    <div class="ref-card rounded-3xl p-6 sm:p-8 space-y-4">
        <div class="border-b border-gray-100 pb-4">
            <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Sales Breakdown by Client Portfolio</h3>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Comprehensive sales volume and revenue contribution per client</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-medium text-gray-700">
                <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Client Business</th>
                        <th class="px-6 py-4 text-center">Transactions</th>
                        <th class="px-6 py-4 text-center">Items Sold</th>
                        <th class="px-6 py-4 text-right">Revenue Contribution</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($salesByClient as $item)
                        <tr class="hover:bg-emerald-50/40 transition-colors">
                            <td class="px-6 py-4 font-extrabold text-gray-900 text-sm">{{ $item->client->business_name ?? $item->client->name }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900">{{ number_format($item->sales_count) }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900">{{ number_format($item->items) }} units</td>
                            <td class="px-6 py-4 text-right font-extrabold text-[#1E8E3E] text-sm">LKR {{ number_format($item->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium">
                                No client sales report data available yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-ref-layout>
