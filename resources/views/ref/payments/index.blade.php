<x-ref-layout>
    <x-slot name="header">
        Client Payments Status & History
    </x-slot>

    <!-- Filter & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900/90 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Client Payments Audit Log</h2>
            <p class="text-xs text-slate-400 mt-0.5">Track bank slips and payment verification statuses for assigned clients</p>
        </div>

        <form method="GET" action="{{ route('ref.payments.index') }}" class="flex items-center gap-3">
            <select name="status" class="px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                <option value="">All Payment Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-md">
                Filter
            </button>
            @if(request()->filled('status'))
                <a href="{{ route('ref.payments.index') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Clear</a>
            @endif
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/70 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">Payment #</th>
                        <th class="px-5 py-3.5">Client Business</th>
                        <th class="px-5 py-3.5">Bank & Ref #</th>
                        <th class="px-5 py-3.5">Amount</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4 font-bold text-emerald-400">{{ $payment->payment_number }}</td>
                            <td class="px-5 py-4 font-medium text-white">{{ $payment->client->business_name ?? $payment->client->name }}</td>
                            <td class="px-5 py-4 text-slate-300">
                                <div class="font-semibold text-white">{{ $payment->bank_name }}</div>
                                <div class="text-[11px] text-slate-400">Ref: {{ $payment->reference_number }}</div>
                            </td>
                            <td class="px-5 py-4 font-extrabold text-white">LKR {{ number_format($payment->amount, 2) }}</td>
                            <td class="px-5 py-4">
                                @if($payment->status === 'approved')
                                    <span class="px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-semibold text-[11px] rounded-full">
                                        Approved
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-semibold text-[11px] rounded-full">
                                        Pending Admin Review
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-semibold text-[11px] rounded-full">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-400">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('ref.payments.show', $payment->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-emerald-600 text-slate-200 hover:text-white font-semibold text-xs rounded-lg transition-colors">
                                    View Slip & Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                                No client payment records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $payments->links() }}
        </div>
    </div>
</x-ref-layout>
