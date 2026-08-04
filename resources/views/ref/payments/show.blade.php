<x-ref-layout>
    <x-slot name="header">
        Payment Details & Receipt Verification
    </x-slot>

    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ref.payments.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 font-semibold">
            &larr; Back to Payments List
        </a>
    </div>

    <!-- Payment Detail Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div>
                    <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider">Payment Record</span>
                    <h2 class="text-2xl font-extrabold text-white mt-0.5">{{ $payment->payment_number }}</h2>
                </div>
                <div>
                    @if($payment->status === 'approved')
                        <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-xs rounded-full">
                            Approved
                        </span>
                    @elseif($payment->status === 'pending')
                        <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold text-xs rounded-full">
                            Pending Review
                        </span>
                    @else
                        <span class="px-3 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold text-xs rounded-full">
                            Rejected
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                    <span class="text-slate-500 block">Client Account</span>
                    <span class="font-bold text-white text-sm block mt-0.5">{{ $payment->client->business_name ?? $payment->client->name }}</span>
                    <span class="text-slate-400 text-[11px]">{{ $payment->client->email }}</span>
                </div>

                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                    <span class="text-slate-500 block">Payment Amount</span>
                    <span class="font-extrabold text-emerald-400 text-lg block mt-0.5">LKR {{ number_format($payment->amount, 2) }}</span>
                </div>

                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                    <span class="text-slate-500 block">Bank Name</span>
                    <span class="font-bold text-white block mt-0.5">{{ $payment->bank_name }}</span>
                </div>

                <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                    <span class="text-slate-500 block">Reference Number</span>
                    <span class="font-bold text-white block mt-0.5">{{ $payment->reference_number }}</span>
                </div>
            </div>

            @if($payment->rejection_reason)
                <div class="p-4 bg-rose-950/40 border border-rose-500/30 rounded-xl text-xs text-rose-300">
                    <span class="font-bold block uppercase mb-1">Rejection Reason:</span>
                    {{ $payment->rejection_reason }}
                </div>
            @endif
        </div>

        <!-- Payment Screenshot Slip Preview -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-3">Bank Slip Screenshot</h3>

            @if($payment->payment_screenshot)
                <div class="overflow-hidden rounded-xl border border-slate-800">
                    <img src="{{ Storage::url($payment->payment_screenshot) }}" alt="Payment Slip" class="w-full h-auto object-cover hover:scale-105 transition-transform">
                </div>
            @else
                <div class="p-8 text-center text-xs text-slate-500 bg-slate-950/60 rounded-xl border border-slate-800">
                    No screenshot attached.
                </div>
            @endif
        </div>
    </div>
</x-ref-layout>
