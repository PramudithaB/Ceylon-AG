<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('payments.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 transition-colors print:hidden">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                        Payment Voucher #{{ $payment->payment_number }}
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Bank transfer proof receipt details</p>
                </div>
            </div>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 transition-all print:hidden">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Voucher
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-8 shadow-sm space-y-6">

                <!-- Header Badge -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-6 gap-4">
                    <div>
                        <span class="text-xs uppercase tracking-widest font-bold text-slate-400">Payment Reference Number</span>
                        <div class="text-2xl font-black font-mono text-emerald-600 dark:text-emerald-400 mt-1">{{ $payment->payment_number }}</div>
                    </div>
                    <div>
                        @if($payment->isPending())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                ⏳ Pending Administrative Verification
                            </span>
                        @elseif($payment->isApproved())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                ✓ Approved & Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                ✗ Rejected
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Grid Data -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold block mb-1">Submitted Amount</span>
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
                        <span class="text-slate-400 font-semibold block mb-1">Bank Reference / Slip #</span>
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
                        <span class="text-[11px] uppercase tracking-wider font-bold text-rose-600 dark:text-rose-400 block mb-1">Administrative Rejection Reason</span>
                        <p class="text-xs text-rose-700 dark:text-rose-300 font-semibold">{{ $payment->rejection_reason }}</p>
                    </div>
                @endif

                <!-- Payment Screenshot Slip Preview -->
                <div class="border-t border-slate-100 dark:border-slate-800 pt-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Uploaded Payment Receipt Slip</h4>
                        <a href="{{ $payment->receipt_url }}" target="_blank" class="text-xs text-emerald-600 font-semibold hover:underline">
                            Open Original File &nearr;
                        </a>
                    </div>

                    <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-2">
                        @if(str_contains(strtolower($payment->payment_screenshot), '.pdf'))
                            <iframe src="{{ $payment->receipt_url }}" class="w-full h-96 rounded-xl"></iframe>
                        @else
                            <img src="{{ $payment->receipt_url }}" alt="Payment Receipt Slip" class="max-h-96 mx-auto object-contain rounded-xl">
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
