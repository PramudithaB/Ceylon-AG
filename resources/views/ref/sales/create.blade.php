<x-ref-layout>
    <x-slot name="header">
        Record Client Retail Sale
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-5">
        <!-- Back Link -->
        <a href="{{ route('ref.sales.index') }}" class="text-xs font-bold text-gray-500 hover:text-[#1E8E3E] inline-flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Sales History</span>
        </a>

        <div class="ref-card rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Record Retail Sale</h2>
                <p class="text-xs text-gray-500 font-medium mt-1">Record a sale from assigned inventory on behalf of a client shop</p>
            </div>

            <form method="POST" action="{{ route('ref.sales.store') }}" class="space-y-4">
                @csrf

                <!-- Client Selector -->
                <div>
                    <label for="client_id" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Client Shop *</label>
                    <select id="client_id" name="client_id" onchange="window.location.href='{{ route('ref.sales.create') }}?client_id=' + this.value" required class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-xs">
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

                @if($selectedClient)
                    <!-- Product from Client's Available Stock -->
                    <div>
                        <label for="product_id" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Product in Client Stock *</label>
                        @if($availableProducts->isEmpty())
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold">
                                This client currently has no assigned product stock available to sell. Please submit a stock request first.
                            </div>
                        @else
                            <select id="product_id" name="product_id" required class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-xs">
                                <option value="" disabled selected>-- Select Product --</option>
                                @foreach($availableProducts as $prod)
                                    <option value="{{ $prod->product_id }}">
                                        {{ $prod->product->name }} &bull; Available: {{ $prod->remaining_qty }} units &bull; Price: LKR {{ number_format($prod->selling_price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        @error('product_id')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Quantity Sold *</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', 1) }}" required class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-xs">
                        @error('quantity')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer / Farmer Name -->
                    <div>
                        <label for="customer_name" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Customer / Farmer Name <span class="text-gray-400 font-normal lowercase">(optional)</span></label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="e.g. Kasun Fernando" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-xs">
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Notes (optional)</label>
                        <input type="text" id="notes" name="notes" value="{{ old('notes') }}" placeholder="Additional order comments" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-xs">
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end">
                        <button type="submit" {{ $availableProducts->isEmpty() ? 'disabled' : '' }} class="touch-btn w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Record Sale</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-ref-layout>
