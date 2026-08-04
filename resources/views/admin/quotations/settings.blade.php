<x-admin-layout>
    <x-slot name="header">
        Quotation & Company Settings
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-white tracking-tight">Company Branding & Bank Settings</h2>
                <p class="text-xs text-slate-400 mt-0.5">Configure default corporate header details, bank accounts, and quotation terms</p>
            </div>
            <a href="{{ route('admin.quotations.index') }}" class="text-xs font-bold text-slate-400 hover:text-white">
                &larr; Back to Quotations
            </a>
        </div>

        <form method="POST" action="{{ route('admin.quotations.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Company Branding Card -->
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl space-y-4">
                <h3 class="text-base font-extrabold text-emerald-400 border-b border-slate-800 pb-3">Company Header & Contact Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="company_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Company Name *</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $settings->company_name) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Company Physical Address *</label>
                        <textarea id="address" name="address" rows="2" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">{{ old('address', $settings->address) }}</textarea>
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Phone Number *</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $settings->phone) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Corporate Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $settings->email) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="website" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Website URL</label>
                        <input type="text" id="website" name="website" value="{{ old('website', $settings->website) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="logo" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Company Logo</label>
                        <input type="file" id="logo" name="logo" accept="image/*" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700">
                    </div>
                </div>
            </div>

            <!-- Bank Details Card -->
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl space-y-4">
                <h3 class="text-base font-extrabold text-emerald-400 border-b border-slate-800 pb-3">Corporate Bank Account Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bank_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Bank Name *</label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="branch" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Branch Name *</label>
                        <input type="text" id="branch" name="branch" value="{{ old('branch', $settings->branch) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="account_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Account Holder Name *</label>
                        <input type="text" id="account_name" name="account_name" value="{{ old('account_name', $settings->account_name) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="account_number" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Account Number *</label>
                        <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $settings->account_number) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="swift_code" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Swift Code (Optional)</label>
                        <input type="text" id="swift_code" name="swift_code" value="{{ old('swift_code', $settings->swift_code) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="default_delivery_period" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Default Delivery Period</label>
                        <input type="text" id="default_delivery_period" name="default_delivery_period" value="{{ old('default_delivery_period', $settings->default_delivery_period) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label for="default_terms" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Default Terms & Conditions</label>
                        <textarea id="default_terms" name="default_terms" rows="4" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">{{ old('default_terms', $settings->default_terms) }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs rounded-xl shadow-lg transition-all">
                        Save Corporate Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
