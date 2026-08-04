<x-ref-layout>
    <x-slot name="header">
        Submit Product Request to Admin
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Back Link -->
        <a href="{{ route('ref.stock-requests.index') }}" class="text-xs text-slate-400 hover:text-white inline-flex items-center gap-1 font-semibold">
            &larr; Back to Stock Requests
        </a>

        <!-- Form Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl space-y-6">
            <div>
                <h2 class="text-xl font-extrabold text-white tracking-tight">Submit Product Request</h2>
                <p class="text-xs text-slate-400 mt-1">Request additional inventory allocation from Admin for an assigned client</p>
            </div>

            <form method="POST" action="{{ route('ref.stock-requests.store') }}" class="space-y-5">
                @csrf

                <!-- Client Select -->
                <div>
                    <label for="client_id" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Select Client *</label>
                    <select id="client_id" name="client_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        <option value="" disabled selected>Choose a client from portfolio</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->business_name ?? $client->name }} ({{ $client->district }})
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Select -->
                <div>
                    <label for="product_id" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Select Product *</label>
                    <select id="product_id" name="product_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        <option value="" disabled selected>Choose a product item</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} &mdash; Stock: {{ $product->stock }} (SKU: {{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requested Quantity -->
                <div>
                    <label for="requested_quantity" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Requested Quantity *</label>
                    <input type="number" id="requested_quantity" name="requested_quantity" min="1" value="{{ old('requested_quantity', 1) }}" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="Enter quantity needed">
                    @error('requested_quantity')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Request Notes & Justification (Optional)</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="Provide notes regarding delivery urgency or client order requirements...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-800 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all">
                        Submit Product Request to Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-ref-layout>
