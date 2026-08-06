<x-ref-layout>
    <x-slot name="header">
        Sales History & Transactions
    </x-slot>

    <!-- Header Action Card -->
    <div class="ref-card rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Client Sales History</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Transactions recorded for your assigned client portfolio</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('ref.sales.reports') }}" class="px-5 py-2.5 bg-emerald-50 hover:bg-[#1E8E3E] text-[#1E8E3E] hover:text-white font-extrabold text-xs rounded-2xl transition-all border border-emerald-200 flex items-center gap-2">
                <span>View Sales Reports</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </a>
        </div>
    </div>

    <!-- Sales History Table -->
    <div class="ref-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-medium text-gray-700">
                <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Sale #</th>
                        <th class="px-6 py-4">Client Business</th>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4 text-center">Qty</th>
                        <th class="px-6 py-4">Unit Price</th>
                        <th class="px-6 py-4">Total Amount</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-emerald-50/40 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#1E8E3E]">{{ $sale->sale_number }}</td>
                            <td class="px-6 py-4 font-extrabold text-gray-900">{{ $sale->client->business_name ?? $sale->client->name }}</td>
                            <td class="px-6 py-4 text-gray-900 font-bold">{{ $sale->product->name ?? 'Product' }}</td>
                            <td class="px-6 py-4 text-center font-extrabold text-gray-900">{{ number_format($sale->quantity) }}</td>
                            <td class="px-6 py-4 text-gray-700 font-bold">LKR {{ number_format($sale->unit_price, 2) }}</td>
                            <td class="px-6 py-4 font-extrabold text-[#1E8E3E]">LKR {{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-gray-500 font-medium">{{ $sale->sold_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-medium">
                                No sales transactions recorded.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $sales->links() }}
        </div>
    </div>
</x-ref-layout>
