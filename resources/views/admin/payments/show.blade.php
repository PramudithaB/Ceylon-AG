<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.payments.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Review Payment #{{ $payment->payment_number }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Verify bank deposit details and authorize payment status</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ rejectModalOpen: false }">

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-8 shadow-sm space-y-6">

            <!-- Header status row -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-6 gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest font-bold text-slate-400">Payment Reference Number</span>
                    <div class="text-2xl font-black font-mono text-emerald-600 dark:text-emerald-400 mt-1">{{ $payment->payment_number }}</div>
                </div>

                <div class="flex items-center space-x-3">
                    @if($payment->isPending())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                            ⏳ Pending Verification
                        </span>

                        <form method="POST" action="{{ route('admin.payments.approve', $payment->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Approve this payment submission?')" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs shadow-md shadow-emerald-600/20">
                                Approve Payment
                            </button>
                        </form>

                        <button @click="rejectModalOpen = true" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs">
                            Reject Payment
                        </button>
                    @elseif($payment->isApproved())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            ✓ Approved by {{ $payment->reviewer->name ?? 'Admin' }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                            ✗ Rejected
                        </span>
                    @endif
                </div>
            </div>

            <!-- Client Info Grid -->
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 font-semibold block mb-1">Client Name</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $payment->client->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold block mb-1">Business Name</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $payment->client->business_name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold block mb-1">Client Email & Phone</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $payment->client->email }} | {{ $payment->client->phone ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Payment Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                <div>
                    <span class="text-slate-400 font-semibold block mb-1">Payment Amount</span>
                    <span class="text-2xl font-black text-slate-900 dark:text-white">LKR {{ number_format($payment->amount, 2) }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-semibold block mb-1">Payment Date</span>
                    <span class="text-base font-bold text-slate-800 dark:text-slate-200">{{ $payment->payment_date->format('F d, Y') }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-semibold block mb-1">Bank Name</span>
                    <span class="text-base font-bold text-slate-800 dark:text-slate-200">{{ $payment->bank_name }}</span>
                </div>

                <div>
                    <span class="text-slate-400 font-semibold block mb-1">Bank Slip / Reference Number</span>
                    <span class="text-base font-mono font-bold text-slate-800 dark:text-slate-200">{{ $payment->reference_number }}</span>
                </div>
            </div>

            @if($payment->remarks)
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800">
                    <span class="text-[11px] uppercase tracking-wider font-semibold text-slate-400 block mb-1">Client Remarks</span>
                    <p class="text-xs text-slate-700 dark:text-slate-300">{{ $payment->remarks }}</p>
                </div>
            @endif

            @if($payment->isRejected() && $payment->rejection_reason)
                <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900">
                    <span class="text-[11px] uppercase tracking-wider font-bold text-rose-600 dark:text-rose-400 block mb-1">Rejection Reason</span>
                    <p class="text-xs text-rose-700 dark:text-rose-300 font-semibold">{{ $payment->rejection_reason }}</p>
                </div>
            @endif

            <!-- Screenshot Slip Preview -->
            <div class="border-t border-slate-100 dark:border-slate-800 pt-6 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Uploaded Payment Receipt Slip</h4>
                    <a href="{{ $payment->receipt_url }}" target="_blank" class="text-xs text-emerald-600 font-semibold hover:underline">
                        Open Original File &nearr;
                    </a>
                </div>

                <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-2">
                    @if(str_contains(strtolower($payment->payment_screenshot), '.pdf'))
                        <iframe src="{{ $payment->receipt_url }}" class="w-full h-[500px] rounded-xl"></iframe>
                    @else
                        <img src="{{ $payment->receipt_url }}" alt="Payment Receipt Slip" class="max-h-[500px] mx-auto object-contain rounded-xl">
                    @endif
                </div>
            </div>

        </div>

        <!-- Rejection Modal -->
        <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Reject Payment #{{ $payment->payment_number }}</h3>
                <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="rejection_reason" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Rejection Reason</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950" placeholder="State reason for rejecting payment..."></textarea>
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
