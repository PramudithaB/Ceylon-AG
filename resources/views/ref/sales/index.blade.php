<x-ref-layout>
    <x-slot name="header">
        Sales History & Transactions
    </x-slot>

    <!-- Header & Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900/90 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Client Sales History</h2>
            <p class="text-xs text-slate-400 mt-0.5">Transactions recorded for your assigned client portfolio</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('ref.sales.reports') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-emerald-400 font-semibold text-xs rounded-xl transition-all border border-emerald-500/30">
                View Sales Reports &rarr;
            </a>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/70 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">Sale #</th>
                        <th class="px-5 py-3.5">Client Business</th>
                        <th class="px-5 py-3.5">Product</th>
                        <th class="px-5 py-3.5">Qty</th>
                        <th class="px-5 py-3.5">Unit Price</th>
                        <th class="px-5 py-3.5">Total Amount</th>
                        <th class="px-5 py-3.5">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4 font-bold text-emerald-400">{{ $sale->sale_number }}</td>
                            <td class="px-5 py-4 font-medium text-white">{{ $sale->client->business_name ?? $sale->client->name }}</td>
                            <td class="px-5 py-4 text-slate-200 font-semibold">{{ $sale->product->name ?? 'Product' }}</td>
                            <td class="px-5 py-4 font-bold text-white">{{ number_format($sale->quantity) }}</td>
                            <td class="px-5 py-4 text-slate-300">LKR {{ number_format($sale->unit_price, 2) }}</td>
                            <td class="px-5 py-4 font-extrabold text-white">LKR {{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-5 py-4 text-slate-400">{{ $sale->sold_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                                No sales transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $sales->links() }}
        </div>
    </div>
</x-ref-layout>
