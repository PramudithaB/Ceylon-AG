<x-ref-layout>
    <x-slot name="header">
        Payment Details & Receipt Verification
    </x-slot>

    <!-- Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ref.payments.index') }}" class="text-xs font-bold text-gray-500 hover:text-[#1E8E3E] flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Payments Log</span>
        </a>
    </div>

    <!-- Payment Detail Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Primary Details -->
        <div class="lg:col-span-2 ref-card rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <span class="text-[11px] font-bold text-[#1E8E3E] uppercase tracking-wider">Payment Record</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-0.5">{{ $payment->payment_number }}</h2>
                </div>
                <div>
                    @if($payment->status === 'approved')
                        <span class="px-4 py-1.5 bg-emerald-50 border border-emerald-200 text-[#1E8E3E] font-extrabold text-xs rounded-full">
                            Approved Verification
                        </span>
                    @elseif($payment->status === 'pending')
                        <span class="px-4 py-1.5 bg-amber-50 border border-amber-200 text-amber-700 font-extrabold text-xs rounded-full">
                            Pending Review
                        </span>
                    @else
                        <span class="px-4 py-1.5 bg-rose-50 border border-rose-200 text-rose-600 font-extrabold text-xs rounded-full">
                            Rejected Payment
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium">
                <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                    <span class="text-gray-400 font-semibold block text-[11px] uppercase">Client Business</span>
                    <span class="font-extrabold text-gray-900 text-base block mt-1">{{ $payment->client->business_name ?? $payment->client->name }}</span>
                    <span class="text-gray-500 text-xs font-medium">{{ $payment->client->email }}</span>
                </div>

                <div class="bg-emerald-50/60 p-5 rounded-2xl border border-emerald-100">
                    <span class="text-gray-500 font-semibold block text-[11px] uppercase">Payment Amount</span>
                    <span class="font-extrabold text-[#1E8E3E] text-2xl block mt-1">LKR {{ number_format($payment->amount, 2) }}</span>
                </div>

                <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                    <span class="text-gray-400 font-semibold block text-[11px] uppercase">Bank Name</span>
                    <span class="font-extrabold text-gray-900 text-base block mt-1">{{ $payment->bank_name }}</span>
                </div>

                <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                    <span class="text-gray-400 font-semibold block text-[11px] uppercase">Reference Number</span>
                    <span class="font-extrabold text-gray-900 text-base block mt-1">{{ $payment->reference_number }}</span>
                </div>
            </div>

            @if($payment->rejection_reason)
                <div class="p-5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800">
                    <span class="font-extrabold block uppercase mb-1">Rejection Reason:</span>
                    <p class="font-medium leading-relaxed">{{ $payment->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <!-- Bank Slip Screenshot Preview Card -->
        <div class="ref-card rounded-3xl p-6 sm:p-8 space-y-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-3">Bank Slip Screenshot</h3>

            @if($payment->payment_screenshot)
                <div class="overflow-hidden rounded-2xl border border-gray-200 group">
                    <img src="{{ Storage::url($payment->payment_screenshot) }}" alt="Payment Slip" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
            @else
                <div class="p-10 text-center text-xs text-gray-400 font-medium bg-gray-50/60 rounded-2xl border border-gray-100">
                    No screenshot preview attached.
                </div>
            @endif
        </div>
    </div>
</x-ref-layout>
