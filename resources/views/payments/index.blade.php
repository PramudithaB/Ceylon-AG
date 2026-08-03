<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    Bank Payment History
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Track submitted bank slip payments and administrative approval status</p>
            </div>
            <a href="{{ route('payments.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Submit Bank Payment
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Bar -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <form method="GET" action="{{ route('payments.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Search</label>
                        <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Payment #, Bank, Ref..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                    </div>

                    <div>
                        <label for="status" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Status</label>
                        <select id="status" name="status" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                            <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                            <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-xs font-semibold bg-emerald-600 text-white text-center">
                            Filter
                        </button>
                        <a href="{{ route('payments.index') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                                <th class="py-3.5 px-6">Payment Ref #</th>
                                <th class="py-3.5 px-6">Amount</th>
                                <th class="py-3.5 px-6">Payment Date</th>
                                <th class="py-3.5 px-6">Bank & Reference</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6">Receipt Slip</th>
                                <th class="py-3.5 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-6 font-mono font-bold text-emerald-600 dark:text-emerald-400 text-[11px]">
                                        {{ $payment->payment_number }}
                                    </td>

                                    <td class="py-4 px-6 font-black text-slate-900 dark:text-white text-sm">
                                        LKR {{ number_format($payment->amount, 2) }}
                                    </td>

                                    <td class="py-4 px-6 text-slate-600 dark:text-slate-300">
                                        {{ $payment->payment_date->format('M d, Y') }}
                                    </td>

                                    <td class="py-4 px-6">
                                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $payment->bank_name }}</div>
                                        <div class="text-[11px] font-mono text-slate-400">Ref: {{ $payment->reference_number }}</div>
                                    </td>

                                    <td class="py-4 px-6">
                                        @if($payment->isPending())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300">
                                                ⏳ Pending Verification
                                            </span>
                                        @elseif($payment->isApproved())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                                ✓ Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300">
                                                ✗ Rejected
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6">
                                        <a href="{{ $payment->receipt_url }}" target="_blank" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 text-[11px] font-semibold transition-all">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Slip
                                        </a>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('payments.show', $payment->id) }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                            Voucher Details &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                        No bank payment submissions found. Click "Submit Bank Payment" to send payment proof.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payments->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
