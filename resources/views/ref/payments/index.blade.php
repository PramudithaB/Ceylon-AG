<x-ref-layout>
    <x-slot name="header">
        Client Payments & Collections
    </x-slot>

    <div class="space-y-4">
        <!-- Filter & Action Bar -->
        <div class="ref-card rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight">Payments & Collections</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Track collected cash, cheques, and bank transfers</p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('ref.payments.create') }}" class="touch-btn px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Collect Payment</span>
                </a>
            </div>
        </div>

        <!-- Filter / Search Form -->
        <form method="GET" action="{{ route('ref.payments.index') }}" class="ref-card rounded-2xl p-3.5 bg-white flex flex-col sm:flex-row gap-2.5">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payment #, ref, or bank..." class="w-full px-4 py-2.5 pl-9 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-900 focus:bg-white">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="status" class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white font-extrabold text-xs rounded-xl transition-colors">Filter</button>
            @if(request('search') || request('status') || request('client_id'))
                <a href="{{ route('ref.payments.index') }}" class="px-3 py-2.5 text-center text-xs font-bold text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <!-- MOBILE CARDS VIEW (Phones < 640px) -->
        <div class="sm:hidden space-y-2.5">
            @forelse($payments as $payment)
                <a href="{{ route('ref.payments.show', $payment->id) }}" class="ref-card rounded-2xl p-4 space-y-2.5 block bg-white hover:border-amber-300 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-amber-700">#{{ $payment->payment_number }}</span>
                        @if($payment->status === 'approved')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-100 text-[#1E8E3E]">
                                Approved
                            </span>
                        @elseif($payment->status === 'pending')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-100 text-amber-800">
                                Pending Review
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-rose-100 text-rose-700">
                                Rejected
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-gray-900">{{ $payment->client->business_name ?? $payment->client->name }}</h3>
                        <p class="text-[11px] text-gray-500 font-medium mt-0.5">
                            Method: <strong class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Cash')) }}</strong>
                            @if($payment->reference_number) &bull; Ref: {{ $payment->reference_number }} @endif
                        </p>
                    </div>

                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[10px] text-gray-400 font-semibold">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                        <span class="text-sm font-black text-[#1E8E3E]">LKR {{ number_format($payment->amount, 2) }}</span>
                    </div>
                </a>
            @empty
                <div class="ref-card rounded-2xl p-8 text-center text-xs text-gray-400 font-medium">
                    No payment records found.
                </div>
            @endforelse
        </div>

        <!-- DESKTOP TABLE VIEW (Screens >= 640px) -->
        <div class="hidden sm:block ref-card rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium text-gray-700">
                    <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Payment #</th>
                            <th class="px-6 py-4">Client Business</th>
                            <th class="px-6 py-4">Method & Ref</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-amber-50/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-amber-700">{{ $payment->payment_number }}</td>
                                <td class="px-6 py-4 font-extrabold text-gray-900">{{ $payment->client->business_name ?? $payment->client->name }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Cash')) }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium">Ref: {{ $payment->reference_number ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 font-extrabold text-[#1E8E3E] text-sm">LKR {{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($payment->status === 'approved')
                                        <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-[#1E8E3E] font-extrabold text-[11px] rounded-full">
                                            Approved
                                        </span>
                                    @elseif($payment->status === 'pending')
                                        <span class="px-3 py-1 bg-amber-50 border border-amber-200 text-amber-700 font-extrabold text-[11px] rounded-full">
                                            Pending Review
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-rose-50 border border-rose-200 text-rose-600 font-extrabold text-[11px] rounded-full">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 font-medium">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('ref.payments.show', $payment->id) }}" class="px-4 py-2 bg-gray-50 hover:bg-[#1E8E3E] text-gray-700 hover:text-white font-extrabold text-xs rounded-xl transition-all inline-flex items-center gap-1 border border-gray-200">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-medium">
                                    No client payment records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-2">
            {{ $payments->links() }}
        </div>
    </div>
</x-ref-layout>
