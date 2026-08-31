<x-ref-layout>
    <x-slot name="header">
        Collect Client Payment
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-5">
        <!-- Back Link -->
        <a href="{{ route('ref.payments.index') }}" class="text-xs font-bold text-gray-500 hover:text-[#1E8E3E] inline-flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Payments</span>
        </a>

        <div class="ref-card rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Collect Payment</h2>
                <p class="text-xs text-gray-500 font-medium mt-1">Submit collected cash or bank transfer payment for Admin verification</p>
            </div>

            <form method="POST" action="{{ route('ref.payments.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Client Selector -->
                <div>
                    <label for="client_id" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Client Shop *</label>
                    <select id="client_id" name="client_id" required class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 shadow-xs">
                        <option value="" disabled {{ !$selectedClient ? 'selected' : '' }}>-- Select Client --</option>
                        @foreach($assignedClients as $client)
                            <option value="{{ $client->id }}" {{ $selectedClient && $selectedClient->id === $client->id ? 'selected' : '' }}>
                                {{ $client->business_name ?? $client->name }} ({{ $client->district ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Amount (LKR) *</label>
                    <input type="number" step="0.01" id="amount" name="amount" min="1" value="{{ old('amount') }}" required placeholder="e.g. 25000.00" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 shadow-xs">
                    @error('amount')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Payment Method *</label>
                    <select id="payment_method" name="payment_method" required class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 shadow-xs">
                        <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash Collection</option>
                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer / Deposit</option>
                        <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>Card</option>
                        <option value="online" {{ old('payment_method') === 'online' ? 'selected' : '' }}>Online</option>
                    </select>
                </div>

                <!-- Reference & Bank -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="reference_number" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Ref / Slip #</label>
                        <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" placeholder="REF-XXXX" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 shadow-xs">
                    </div>
                    <div>
                        <label for="bank_name" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Bank Name</label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" placeholder="e.g. Commercial Bank" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 shadow-xs">
                    </div>
                </div>

                <!-- Screenshot -->
                <div>
                    <label for="payment_screenshot" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Deposit Slip / Receipt Photo</label>
                    <input type="file" id="payment_screenshot" name="payment_screenshot" accept="image/*,.pdf" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-700 shadow-xs file:mr-3 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-emerald-50 file:text-[#1E8E3E]">
                </div>

                <!-- Remarks -->
                <div>
                    <label for="remarks" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Remarks (optional)</label>
                    <textarea id="remarks" name="remarks" rows="3" placeholder="Additional collection remarks" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 shadow-xs">{{ old('remarks') }}</textarea>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="touch-btn w-full sm:w-auto px-8 py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Submit Payment</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-ref-layout>
