<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('payments.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    Submit Bank Payment Proof
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Upload deposit slip or online transfer confirmation screenshot for Ceylon AG verification</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
                @csrf

                <!-- Amount & Date Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="amount" :value="__('Payment Amount (LKR)')" />
                        <x-text-input id="amount" class="block mt-1 w-full text-xs" type="number" step="0.01" min="1" name="amount" :value="old('amount')" required placeholder="e.g. 50000.00" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="payment_date" :value="__('Payment Date')" />
                        <x-text-input id="payment_date" class="block mt-1 w-full text-xs" type="date" name="payment_date" :value="old('payment_date', date('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('payment_date')" class="mt-1" />
                    </div>
                </div>

                <!-- Bank Name & Reference Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="bank_name" :value="__('Bank Name')" />
                        <x-text-input id="bank_name" class="block mt-1 w-full text-xs" type="text" name="bank_name" :value="old('bank_name')" required placeholder="e.g. Commercial Bank / Sampath Bank" />
                        <x-input-error :messages="$errors->get('bank_name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="reference_number" :value="__('Bank Slip / Reference Number')" />
                        <x-text-input id="reference_number" class="block mt-1 w-full text-xs" type="text" name="reference_number" :value="old('reference_number')" required placeholder="e.g. TXN-99882211 or Slip Ref" />
                        <x-input-error :messages="$errors->get('reference_number')" class="mt-1" />
                    </div>
                </div>

                <!-- Payment Screenshot Upload -->
                <div>
                    <x-input-label for="payment_screenshot" :value="__('Upload Payment Slip Screenshot (JPG, PNG, PDF)')" />
                    <input id="payment_screenshot" name="payment_screenshot" type="file" required accept="image/*,.pdf" class="block mt-1 w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-950 dark:file:text-emerald-300">
                    <x-input-error :messages="$errors->get('payment_screenshot')" class="mt-1" />
                    <p class="text-[11px] text-slate-400 mt-1">Maximum file size: 5MB. Ensure transaction reference is clearly visible.</p>
                </div>

                <!-- Remarks -->
                <div>
                    <x-input-label for="remarks" :value="__('Payment Remarks (Optional)')" />
                    <textarea id="remarks" name="remarks" rows="3" class="block mt-1 w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-slate-800 dark:text-slate-200" placeholder="e.g. Settlement for Product Allocation #PAS-20260803-0001">{{ old('remarks') }}</textarea>
                    <x-input-error :messages="$errors->get('remarks')" class="mt-1" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('payments.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 transition-all">
                        Cancel
                    </a>
                    <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">
                        Submit Payment Proof
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
