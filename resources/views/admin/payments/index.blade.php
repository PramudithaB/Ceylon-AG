<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Bank Payment Verifications</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Review client deposit slip submissions and execute administrative approvals</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ rejectModalOpen: false, rejectUrl: '', paymentRef: '' }">

        <!-- Search & Filter Bar -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Payment #, Client, Bank, Ref..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                </div>

                <div>
                    <label for="status" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Status</label>
                    <select id="status" name="status" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                        <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>⏳ Pending Verification</option>
                        <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>✓ Approved</option>
                        <option value="rejected" {{ ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' }}>✗ Rejected</option>
                    </select>
                </div>

                <div>
                    <label for="client_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Client Partner</label>
                    <select id="client_id" name="client_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                        <option value="all" {{ ($filters['client_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ ($filters['client_id'] ?? '') == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->business_name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-xs font-semibold bg-emerald-600 text-white text-center">
                        Filter
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Payments Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Payment Ref #</th>
                            <th class="py-3.5 px-6">Client Partner</th>
                            <th class="py-3.5 px-6">Amount</th>
                            <th class="py-3.5 px-6">Payment Date</th>
                            <th class="py-3.5 px-6">Bank & Reference</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6">Slip Receipt</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-4 px-6 font-mono font-bold text-emerald-600 dark:text-emerald-400 text-[11px]">
                                    {{ $payment->payment_number }}
                                </td>

                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $payment->client->name ?? 'N/A' }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $payment->client->business_name ?? '-' }}</div>
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
                                            ⏳ Pending
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
                                        View Slip
                                    </a>
                                </td>

                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300 font-semibold text-[11px] inline-block">
                                        Details
                                    </a>

                                    @if($payment->isPending())
                                        <form method="POST" action="{{ route('admin.payments.approve', $payment->id) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Approve this payment submission?')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-[11px]">
                                                Approve
                                            </button>
                                        </form>

                                        <button @click="rejectModalOpen = true; rejectUrl = '{{ route('admin.payments.reject', $payment->id) }}'; paymentRef = '{{ $payment->payment_number }}'" class="px-2.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-semibold text-[11px]">
                                            Reject
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    No payment submissions found.
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

        <!-- Rejection Reason Modal -->
        <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Reject Payment <span x-text="paymentRef"></span></h3>
                <form :action="rejectUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="rejection_reason" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Rejection Reason</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950" placeholder="e.g. Bank slip reference illegible or payment amount mismatch."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600 text-white">
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>
