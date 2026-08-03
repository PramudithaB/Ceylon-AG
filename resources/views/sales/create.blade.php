<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('sales.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    Record Product Sale
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Log retail sale of products assigned to your inventory</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="POST" action="{{ route('sales.store') }}" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
                @csrf

                <!-- Product Select -->
                <div>
                    <x-input-label for="product_id" :value="__('Select Assigned Product')" />
                    <select id="product_id" name="product_id" required onchange="handleProductSelect(this)" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2.5 px-3">
                        <option value="" disabled selected>-- Choose Product from Inventory --</option>
                        @foreach($assignedProducts as $prod)
                            <option value="{{ $prod['product_id'] }}"
                                    data-remaining="{{ $prod['remaining_qty'] }}"
                                    data-price="{{ $prod['selling_price'] }}"
                                    {{ old('product_id', $selectedProductId) == $prod['product_id'] ? 'selected' : '' }}>
                                {{ $prod['name'] }} (SKU: {{ $prod['sku'] }}) — Remaining Assigned Stock: {{ $prod['remaining_qty'] }} units
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('product_id')" class="mt-1" />

                    <!-- Live stock info badge -->
                    <div id="stock-info" class="mt-2 hidden">
                        <span id="stock-text" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"></span>
                    </div>
                </div>

                <!-- Quantity & Date Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="quantity" :value="__('Sold Quantity')" />
                        <x-text-input id="quantity" class="block mt-1 w-full text-xs" type="number" min="1" name="quantity" :value="old('quantity', 1)" required placeholder="Enter sold quantity" />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="sold_at" :value="__('Selling Date')" />
                        <x-text-input id="sold_at" class="block mt-1 w-full text-xs" type="date" name="sold_at" :value="old('sold_at', date('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('sold_at')" class="mt-1" />
                    </div>
                </div>

                <!-- Customer Name -->
                <div>
                    <x-input-label for="customer_name" :value="__('Customer / Buyer Name (Optional)')" />
                    <x-text-input id="customer_name" class="block mt-1 w-full text-xs" type="text" name="customer_name" :value="old('customer_name')" placeholder="e.g. Bandara Farms / Retail Customer" />
                    <x-input-error :messages="$errors->get('customer_name')" class="mt-1" />
                </div>

                <!-- Notes -->
                <div>
                    <x-input-label for="notes" :value="__('Sale Notes (Optional)')" />
                    <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-slate-800 dark:text-slate-200" placeholder="e.g. Sold with cash payment. Received invoice receipt.">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('sales.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 transition-all">
                        Cancel
                    </a>
                    <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">
                        Record Sale & Deduct Stock
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function handleProductSelect(select) {
            const option = select.options[select.selectedIndex];
            if (!option || !option.dataset) return;

            const remaining = parseInt(option.dataset.remaining || 0);
            const price = parseFloat(option.dataset.price || 0);

            const infoContainer = document.getElementById('stock-info');
            const infoText = document.getElementById('stock-text');

            if (infoContainer && infoText) {
                infoContainer.classList.remove('hidden');
                infoText.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300';
                infoText.innerText = 'Available Stock: ' + remaining + ' units | Retail Price: LKR ' + price.toFixed(2);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('product_id');
            if (select && select.selectedIndex > 0) {
                handleProductSelect(select);
            }
        });
    </script>
</x-app-layout>
