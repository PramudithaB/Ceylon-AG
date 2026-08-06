<x-ref-layout>
    <x-slot name="header">
        Submit Product Request to Admin
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Back Link -->
        <a href="{{ route('ref.stock-requests.index') }}" class="text-xs font-bold text-gray-500 hover:text-[#1E8E3E] inline-flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Stock Requests</span>
        </a>

        <!-- Form Card -->
        <div class="ref-card rounded-3xl p-6 sm:p-10 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Submit Product Request</h2>
                <p class="text-xs text-gray-500 font-medium mt-1">Request additional inventory allocation from Admin for an assigned client</p>
            </div>

            <form method="POST" action="{{ route('ref.stock-requests.store') }}" class="space-y-5">
                @csrf

                <!-- Client Select -->
                <div>
                    <label for="client_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Client *</label>
                    <select id="client_id" name="client_id" required class="auth-input block w-full px-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                        <option value="" disabled selected>Choose a client from portfolio</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->business_name ?? $client->name }} ({{ $client->district }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Select -->
                <div>
                    <label for="product_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Product *</label>
                    <select id="product_id" name="product_id" required class="auth-input block w-full px-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                        <option value="" disabled selected>Choose a product item</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} &mdash; Stock: {{ $product->stock }} (SKU: {{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requested Quantity -->
                <div>
                    <label for="requested_quantity" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Requested Quantity *</label>
                    <input type="number" 
                        id="requested_quantity" 
                        name="requested_quantity" 
                        min="1" 
                        value="{{ old('requested_quantity', 1) }}" 
                        required 
                        placeholder="Enter quantity needed"
                        class="auth-input block w-full px-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white" 
                    />
                    @error('requested_quantity')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Request Notes & Justification (Optional)</label>
                    <textarea id="notes" 
                        name="notes" 
                        rows="4" 
                        placeholder="Provide notes regarding delivery urgency or client order requirements..."
                        class="auth-input block w-full px-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white"
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" 
                        class="px-8 py-4 rounded-2xl text-xs font-extrabold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-xl shadow-emerald-700/25 hover:shadow-emerald-700/40 flex items-center justify-center gap-2">
                        <span>Submit Product Request to Admin</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-ref-layout>
