<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('stock-requests.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    Request Stock Allocation
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Select product and required quantity for central warehouse dispatch</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('stock-requests.store') }}" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
                @csrf

                <div>
                    <x-input-label for="product_id" :value="__('Select Product')" />
                    <select id="product_id" name="product_id" required class="block mt-1 w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500">
                        <option value="">-- Choose Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (SKU: {{ $product->sku }}) - Selling LKR {{ number_format($product->selling_price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('product_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="requested_quantity" :value="__('Required Units Quantity')" />
                    <x-text-input id="requested_quantity" class="block mt-1 w-full text-xs" type="number" min="1" name="requested_quantity" :value="old('requested_quantity')" required placeholder="e.g. 50" />
                    <x-input-error :messages="$errors->get('requested_quantity')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="notes" :value="__('Additional Request Notes (Optional)')" />
                    <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 text-slate-800 dark:text-slate-200" placeholder="e.g. Urgent stock request due to high regional demand in Kurunegala">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('stock-requests.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        Cancel
                    </a>
                    <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">
                        Submit Stock Request
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
