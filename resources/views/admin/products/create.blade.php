<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Add New Product</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Onboard a new agricultural product into the Ceylon AG catalog</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            @csrf

            <!-- Product Image -->
            <div>
                <x-input-label for="image" :value="__('Product Image')" />
                <input type="file" id="image" name="image" accept="image/*" class="mt-2 block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-300 hover:file:bg-emerald-100 cursor-pointer">
                <x-input-error :messages="$errors->get('image')" class="mt-1" />
            </div>

            <!-- Product Name & SKU -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name" :value="__('Product Name')" />
                    <x-text-input id="name" class="block mt-1 w-full text-xs" type="text" name="name" :value="old('name')" required autofocus placeholder="e.g. Organic NPK Fertilizer 50kg" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="sku" :value="__('SKU (Stock Keeping Unit)')" />
                    <x-text-input id="sku" class="block mt-1 w-full text-xs" type="text" name="sku" :value="old('sku')" required placeholder="e.g. AG-FERT-001" />
                    <x-input-error :messages="$errors->get('sku')" class="mt-1" />
                </div>
            </div>

            <!-- Category & Status Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="category_id" :value="__('Category')" />
                    <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2 px-3" required>
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2 px-3" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            <!-- Description -->
            <div>
                <x-input-label for="description" :value="__('Description')" />
                <textarea id="description" name="description" rows="3" class="block mt-1 w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 text-slate-800 dark:text-slate-200" placeholder="Product specifications, usage instructions, composition...">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <!-- Pricing Row (Buying Price, Dealer Price, Selling Price) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="buying_price" :value="__('Buying Price (LKR)')" />
                    <x-text-input id="buying_price" class="block mt-1 w-full text-xs" type="number" step="0.01" min="0" name="buying_price" :value="old('buying_price')" required placeholder="0.00" />
                    <x-input-error :messages="$errors->get('buying_price')" class="mt-1" />
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

            <!-- Stock Quantity & Minimum Stock Threshold Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="stock_quantity" :value="__('Initial Stock Quantity')" />
                    <x-text-input id="stock_quantity" class="block mt-1 w-full text-xs" type="number" min="0" name="stock_quantity" :value="old('stock_quantity', 0)" required placeholder="0" />
                    <x-input-error :messages="$errors->get('stock_quantity')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="minimum_stock" :value="__('Low Stock Alert Limit')" />
                    <x-text-input id="minimum_stock" class="block mt-1 w-full text-xs" type="number" min="0" name="minimum_stock" :value="old('minimum_stock', 5)" required placeholder="5" />
                    <x-input-error :messages="$errors->get('minimum_stock')" class="mt-1" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                    Cancel
                </a>
                <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">
                    Save Product
                </x-primary-button>
            </div>
        </form>
    </div>
</x-admin-layout>
