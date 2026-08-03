<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.product-assignments.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">New Product Stock Assignment</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Allocate warehouse products to approved client accounts</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Stock Deduction & Mail Notice Alert -->
        <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-2xl p-4 flex items-start space-x-3">
            <div class="text-amber-600 dark:text-amber-400 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-xs text-amber-800 dark:text-amber-300">
                <span class="font-bold">System Automation Notice:</span> Submitting this assignment will automatically deduct the requested quantity from warehouse stock inside a secure database transaction and dispatch an email allocation voucher to the selected client.
            </div>
        </div>

        <form method="POST" action="{{ route('admin.product-assignments.store') }}" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            @csrf

            <!-- Client Select -->
            <div>
                <x-input-label for="client_id" :value="__('Select Target Client Account')" />
                <select id="client_id" name="client_id" required class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2.5 px-3">
                    <option value="" disabled selected>-- Choose Approved Client --</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $selectedClientId) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} — {{ $client->business_name }} ({{ $client->district }}, {{ $client->province }})
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            <!-- Product Select -->
            <div>
                <x-input-label for="product_id" :value="__('Select Warehouse Product')" />
                <select id="product_id" name="product_id" required onchange="handleProductChange(this)" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2.5 px-3">
                    <option value="" disabled selected>-- Choose Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                                data-dealer="{{ $product->dealer_price }}"
                                data-selling="{{ $product->selling_price }}"
                                data-stock="{{ $product->stock_quantity }}"
                                {{ old('product_id', $selectedProductId) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (SKU: {{ $product->sku }}) — Available Stock: {{ $product->stock_quantity }} units
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('product_id')" class="mt-1" />

                <!-- Stock Health Live Badge -->
                <div id="stock-badge-container" class="mt-2 hidden">
                    <span id="stock-badge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"></span>
                </div>
            </div>

            <!-- Quantity & Available Stock Limits -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="quantity" :value="__('Quantity to Assign')" />
                    <x-text-input id="quantity" class="block mt-1 w-full text-xs" type="number" min="1" name="quantity" :value="old('quantity', 1)" required placeholder="Enter quantity" />
                    <x-input-error :messages="$errors->get('quantity')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="dealer_price" :value="__('Dealer Price (LKR)')" />
                    <x-text-input id="dealer_price" class="block mt-1 w-full text-xs" type="number" step="0.01" min="0" name="dealer_price" :value="old('dealer_price')" required placeholder="0.00" />
                    <x-input-error :messages="$errors->get('dealer_price')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="selling_price" :value="__('Selling Price (LKR)')" />
                    <x-text-input id="selling_price" class="block mt-1 w-full text-xs" type="number" step="0.01" min="0" name="selling_price" :value="old('selling_price')" required placeholder="0.00" />
                    <x-input-error :messages="$errors->get('selling_price')" class="mt-1" />
                </div>
            </div>

            <!-- Admin Notes -->
            <div>
                <x-input-label for="notes" :value="__('Notes / Dispatch Instructions (Optional)')" />
                <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-slate-800 dark:text-slate-200" placeholder="e.g. Approved under Special Regional Dealer Discount Rate for Q3.">{{ old('notes') }}</textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.product-assignments.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                    Cancel
                </a>
                <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">
                    Confirm & Assign Stock
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Client side script for auto-filling price and stock availability badge -->
    <script>
        function handleProductChange(select) {
            const option = select.options[select.selectedIndex];
            if (!option || !option.dataset) return;

            const dealerPrice = option.dataset.dealer;
            const sellingPrice = option.dataset.selling;
            const stock = parseInt(option.dataset.stock || 0);

            if (dealerPrice && !document.getElementById('dealer_price').value) {
                document.getElementById('dealer_price').value = dealerPrice;
            } else if (dealerPrice) {
                document.getElementById('dealer_price').value = dealerPrice;
            }

            if (sellingPrice && !document.getElementById('selling_price').value) {
                document.getElementById('selling_price').value = sellingPrice;
            } else if (sellingPrice) {
                document.getElementById('selling_price').value = sellingPrice;
            }

            const badgeContainer = document.getElementById('stock-badge-container');
            const badge = document.getElementById('stock-badge');

            if (badgeContainer && badge) {
                badgeContainer.classList.remove('hidden');
                if (stock <= 0) {
                    badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300';
                    badge.innerText = 'Out of Stock (0 units remaining in warehouse)';
                } else if (stock <= 5) {
                    badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300';
                    badge.innerText = 'Low Warehouse Stock (' + stock + ' units remaining)';
                } else {
                    badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300';
                    badge.innerText = 'Warehouse Stock Available (' + stock + ' units)';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('product_id');
            if (select && select.selectedIndex > 0) {
                handleProductChange(select);
            }
        });
    </script>
</x-admin-layout>
