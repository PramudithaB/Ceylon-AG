<x-ref-layout>
    <x-slot name="header">
        Client Payments Status & History
    </x-slot>

    <!-- Filter & Action Bar -->
    <div class="ref-card rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Client Payments Audit Log</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Track bank slips and payment verification statuses for assigned clients</p>
        </div>

        <form method="GET" action="{{ route('ref.payments.index') }}" class="flex items-center gap-3">
            <select name="status" class="auth-input px-4 py-2.5 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                <option value="">All Payment Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-md hover:opacity-95 transition-all">
                Filter
            </button>
            
            @if(request()->filled('status'))
                <a href="{{ route('ref.payments.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-2xl transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Payments Data Table -->
    <div class="ref-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-medium text-gray-700">
                <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Payment #</th>
                        <th class="px-6 py-4">Client Business</th>
                        <th class="px-6 py-4">Bank & Ref #</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-emerald-50/40 transition-colors">
                            <td class="px-6 py-4 font-bold text-[#1E8E3E]">{{ $payment->payment_number }}</td>
                            <td class="px-6 py-4 font-extrabold text-gray-900">{{ $payment->client->business_name ?? $payment->client->name }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $payment->bank_name }}</div>
                                <div class="text-[11px] text-gray-500 font-medium">Ref: {{ $payment->reference_number }}</div>
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
                                <a href="{{ route('ref.payments.show', $payment->id) }}" class="px-4 py-2 bg-emerald-50 hover:bg-[#1E8E3E] text-[#1E8E3E] hover:text-white font-extrabold text-xs rounded-xl transition-all inline-flex items-center gap-1">
                                    <span>View Slip</span>
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

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $payments->links() }}
        </div>
    </div>
</x-ref-layout>
