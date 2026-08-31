<x-ref-layout>
    <x-slot name="header">
        Sales History & Transactions
    </x-slot>

    <div class="space-y-4">
        <!-- Header Action Card -->
        <div class="ref-card rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight">Client Sales History</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Transactions recorded for your assigned client portfolio</p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('ref.sales.create') }}" class="touch-btn px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Record Sale</span>
                </a>
                <a href="{{ route('ref.sales.reports') }}" class="touch-btn px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold text-xs rounded-2xl transition-all flex items-center justify-center gap-1.5">
                    <span>Reports</span>
                </a>
            </div>
        </div>

        <!-- Filter / Search -->
        <form method="GET" action="{{ route('ref.sales.index') }}" class="ref-card rounded-2xl p-3.5 bg-white flex flex-col sm:flex-row gap-2.5">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by sale # or customer..." class="w-full px-4 py-2.5 pl-9 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-900 focus:bg-white">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <select name="client_id" class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700">
                <option value="">All Clients</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->business_name ?? $client->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white font-extrabold text-xs rounded-xl transition-colors">Filter</button>
            @if(request('search') || request('client_id'))
                <a href="{{ route('ref.sales.index') }}" class="px-3 py-2.5 text-center text-xs font-bold text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <!-- MOBILE CARDS VIEW (Phones < 640px) -->
        <div class="sm:hidden space-y-2.5">
            @forelse($sales as $sale)
                <div class="ref-card rounded-2xl p-4 space-y-2 bg-white">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-[#1E8E3E]">#{{ $sale->sale_number }}</span>
                        <span class="text-[10px] text-gray-400 font-semibold">{{ $sale->sold_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-gray-900">{{ $sale->client->business_name ?? $sale->client->name }}</h3>
                        <p class="text-[11px] text-gray-600 font-medium mt-0.5">
                            {{ $sale->product->name ?? 'Product' }} &bull; <strong class="text-gray-900">{{ $sale->quantity }} units</strong>
                        </p>
                    </div>
                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 font-semibold">Total Amount:</span>
                        <span class="text-xs font-black text-[#1E8E3E]">LKR {{ number_format($sale->total_amount, 2) }}</span>
                    </div>
                </div>
            @empty
                <div class="ref-card rounded-2xl p-8 text-center text-xs text-gray-400 font-medium">
                    No sales transactions found.
                </div>
            @endforelse
        </div>

        <!-- DESKTOP TABLE VIEW (Screens >= 640px) -->
        <div class="hidden sm:block ref-card rounded-3xl overflow-hidden">
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
        </div>

        <div class="p-2">
            {{ $sales->links() }}
        </div>
    </div>
</x-ref-layout>
