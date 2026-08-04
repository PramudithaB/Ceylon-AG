<x-admin-layout>
    <x-slot name="header">
        Create Corporate Quotation
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Top Navigation Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('admin.quotations.index') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Quotation Directory
            </a>
            <div class="inline-flex items-center gap-2 text-xs font-extrabold text-emerald-400 bg-emerald-950/80 border border-emerald-500/40 px-3.5 py-1.5 rounded-full shadow-lg shadow-emerald-950/50">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Auto Quotation #: {{ $autoNumber }}
            </div>
        </div>

        <form method="POST" action="{{ route('admin.quotations.store') }}" id="quotationForm" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <!-- Main Form Column (Left 2 cols) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card 1: Customer Details -->
                    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-5 backdrop-blur-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800/80 pb-4">
                            <div>
                                <h2 class="text-lg font-extrabold text-white tracking-tight">Client & Quotation Info</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Select a registered client to auto-fill details or enter manually</p>
                            </div>

                            <div class="w-full sm:w-72">
                                <label for="client_select" class="block text-[11px] font-bold text-emerald-400 uppercase tracking-wider mb-1">Pre-fill Registered Client</label>
                                <select id="client_select" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition-all">
                                    <option value="">-- Select Registered Client --</option>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}"
                                            data-name="{{ $c->name }}"
                                            data-business="{{ $c->business_name }}"
                                            data-address="{{ $c->address }}"
                                            data-phone="{{ $c->phone }}"
                                            data-email="{{ $c->email }}"
                                            data-district="{{ $c->district }}">
                                            {{ $c->business_name ? $c->business_name . ' (' . $c->name . ')' : $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Customer Contact Name *</label>
                                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="e.g. Mr. John Perera">
                                @error('customer_name') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="business_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Business / Company Name</label>
                                <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="e.g. Agro Lanka Holdings">
                                @error('business_name') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="+94 77 123 4567">
                                @error('phone') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="client@domain.com">
                                @error('email') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="district" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">District</label>
                                <input type="text" id="district" name="district" value="{{ old('district') }}" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="e.g. Kurunegala, Gampaha">
                                @error('district') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Billing Address</label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="No 12, Main Street, Town">
                                @error('address') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-slate-800/80">
                            <div>
                                <label for="quotation_date" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Quotation Date *</label>
                                <input type="date" id="quotation_date" name="quotation_date" value="{{ old('quotation_date', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                                @error('quotation_date') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="expiry_date" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Valid Until (Expiry) *</label>
                                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', date('Y-m-d', strtotime('+30 days'))) }}" required class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                                @error('expiry_date') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Initial Status *</label>
                                <select id="status" name="status" required class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="accepted" {{ old('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                </select>
                                @error('status') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Dynamic Products Table -->
                    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4 backdrop-blur-sm">
                        <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
                            <div>
                                <h2 class="text-lg font-extrabold text-white tracking-tight">Products & Line Items</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Add items from product catalog or create custom items</p>
                            </div>

                            <button type="button" id="addRowBtn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-900/30 transition-all duration-200 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Product Line
                            </button>
                        </div>

                        @error('items')
                            <div class="p-3 bg-rose-950/60 border border-rose-800 rounded-xl text-xs text-rose-300">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-300" id="itemsTable">
                                <thead class="bg-slate-950/80 text-slate-400 uppercase font-semibold border-b border-slate-800">
                                    <tr>
                                        <th class="px-3 py-3 w-56">Product Select</th>
                                        <th class="px-3 py-3">Product Name & Description</th>
                                        <th class="px-3 py-3 w-20 text-center">Qty *</th>
                                        <th class="px-3 py-3 w-28 text-right">Unit Price *</th>
                                        <th class="px-3 py-3 w-24 text-right">Discount</th>
                                        <th class="px-3 py-3 w-28 text-right">Total (LKR)</th>
                                        <th class="px-3 py-3 w-12 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsContainer" class="divide-y divide-slate-800/60">
                                    <!-- Dynamic Rows rendered via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card 3: Bank Details & Terms -->
                    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-5 backdrop-blur-sm">
                        <h2 class="text-lg font-extrabold text-white tracking-tight border-b border-slate-800/80 pb-3">Bank Details & Quotation Terms</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="bank_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Bank Name</label>
                                <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="bank_branch" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Branch</label>
                                <input type="text" id="bank_branch" name="bank_branch" value="{{ old('bank_branch', $settings->branch) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="account_number" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Account Number</label>
                                <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $settings->account_number) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="account_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Account Name</label>
                                <input type="text" id="account_name" name="account_name" value="{{ old('account_name', $settings->account_name) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="swift_code" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Swift Code</label>
                                <input type="text" id="swift_code" name="swift_code" value="{{ old('swift_code', $settings->swift_code) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="delivery_period" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Delivery Period</label>
                                <input type="text" id="delivery_period" name="delivery_period" value="{{ old('delivery_period', $settings->default_delivery_period) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="notes" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Special Notes / Instructions</label>
                                <textarea id="notes" name="notes" rows="3" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" placeholder="e.g. Price includes transport to farm site...">{{ old('notes') }}</textarea>
                            </div>

                            <div>
                                <label for="terms_conditions" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Terms & Conditions</label>
                                <textarea id="terms_conditions" name="terms_conditions" rows="3" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">{{ old('terms_conditions', $settings->default_terms) }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="prepared_by" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Prepared By</label>
                                <input type="text" id="prepared_by" name="prepared_by" value="{{ old('prepared_by', auth()->user()->name) }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="approved_by" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Approved By</label>
                                <input type="text" id="approved_by" name="approved_by" value="{{ old('approved_by', 'Managing Director') }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sticky Summary & Action Card -->
                <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-5 backdrop-blur-sm">
                        <h2 class="text-lg font-extrabold text-white tracking-tight border-b border-slate-800/80 pb-3 flex items-center justify-between">
                            <span>Quotation Summary</span>
                            <span class="text-xs text-emerald-400 font-bold bg-emerald-950/80 border border-emerald-500/30 px-2.5 py-0.5 rounded-full">Live Totals</span>
                        </h2>

                        <div class="space-y-3.5">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-400 font-medium">Items Subtotal:</span>
                                <span class="font-extrabold text-white text-sm" id="subtotalDisplay">LKR 0.00</span>
                            </div>

                            <div class="space-y-1">
                                <label for="discount_amount" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Overall Discount (LKR)</label>
                                <input type="number" step="0.01" min="0" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', 0) }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-right text-xs font-bold text-rose-400 focus:border-emerald-500 focus:outline-none">
                            </div>

                            <div class="space-y-1">
                                <label for="tax_amount" class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Tax / Handling (LKR)</label>
                                <input type="number" step="0.01" min="0" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', 0) }}" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-xl text-right text-xs font-bold text-white focus:border-emerald-500 focus:outline-none">
                            </div>

                            <div class="pt-4 border-t border-slate-800/80 space-y-1 bg-slate-950/60 p-4 rounded-xl border border-emerald-950/80">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Grand Total</span>
                                <div class="text-2xl font-black text-emerald-400 tracking-tight" id="grandTotalDisplay">LKR 0.00</div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-3 space-y-2.5">
                            <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-emerald-500/20 transition-all duration-200">
                                Save Quotation Now
                            </button>
                            <a href="{{ route('admin.quotations.index') }}" class="block w-full py-2.5 text-center bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl transition-all">
                                Cancel & Return
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Product Options Data Object -->
    <script>
        window.availableProducts = {!! json_encode($products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float) ($p->dealer_price ?? $p->selling_price ?? $p->price ?? 0),
            'sku' => $p->sku ?? ''
        ])) !!};
    </script>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Client Dropdown Autofill
            const clientSelect = document.getElementById('client_select');
            if (clientSelect) {
                clientSelect.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    if (selected && selected.value) {
                        document.getElementById('client_id').value = selected.value;
                        document.getElementById('customer_name').value = selected.getAttribute('data-name') || '';
                        document.getElementById('business_name').value = selected.getAttribute('data-business') || selected.getAttribute('data-name') || '';
                        document.getElementById('address').value = selected.getAttribute('data-address') || '';
                        document.getElementById('phone').value = selected.getAttribute('data-phone') || '';
                        document.getElementById('email').value = selected.getAttribute('data-email') || '';
                        document.getElementById('district').value = selected.getAttribute('data-district') || '';
                    }
                });
            }

            // Dynamic Rows Management
            const container = document.getElementById('itemsContainer');
            const addRowBtn = document.getElementById('addRowBtn');
            let rowIdx = 0;

            function addRow(data = {}) {
                const tr = document.createElement('tr');
                tr.className = 'item-row hover:bg-slate-800/30 transition-colors';

                let productOptionsHtml = '<option value="">-- Select Product --</option>';
                if (Array.isArray(window.availableProducts)) {
                    window.availableProducts.forEach(p => {
                        const selected = (data.product_id == p.id) ? 'selected' : '';
                        productOptionsHtml += `<option value="${p.id}" data-price="${p.price}" data-name="${p.name}" ${selected}>${p.name} (LKR ${parseFloat(p.price).toFixed(2)})</option>`;
                    });
                }

                tr.innerHTML = `
                    <td class="px-3 py-3 align-top">
                        <select name="items[${rowIdx}][product_id]" class="product-select w-full px-2.5 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:border-emerald-500 focus:outline-none">
                            ${productOptionsHtml}
                        </select>
                    </td>
                    <td class="px-3 py-3 align-top space-y-1.5">
                        <input type="text" name="items[${rowIdx}][product_name]" value="${data.product_name || ''}" placeholder="Product Name *" required class="product-name-input w-full px-2.5 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-xs font-semibold text-white focus:border-emerald-500 focus:outline-none">
                        <textarea name="items[${rowIdx}][description]" rows="1" placeholder="Description / Specs..." class="w-full px-2.5 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-xs text-slate-300 focus:border-emerald-500 focus:outline-none">${data.description || ''}</textarea>
                    </td>
                    <td class="px-3 py-3 align-top">
                        <input type="number" name="items[${rowIdx}][quantity]" value="${data.quantity || 1}" min="1" step="1" required class="qty-input w-full px-2 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-xs font-bold text-white text-center focus:border-emerald-500 focus:outline-none">
                    </td>
                    <td class="px-3 py-3 align-top">
                        <input type="number" step="0.01" min="0.01" name="items[${rowIdx}][unit_price]" value="${data.unit_price || 0}" required class="price-input w-full px-2 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-xs font-bold text-white text-right focus:border-emerald-500 focus:outline-none">
                    </td>
                    <td class="px-3 py-3 align-top">
                        <input type="number" step="0.01" min="0" name="items[${rowIdx}][discount]" value="${data.discount || 0}" class="discount-input w-full px-2 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-xs text-rose-400 text-right focus:border-emerald-500 focus:outline-none">
                    </td>
                    <td class="px-3 py-3 align-top text-right font-black text-emerald-400 line-total text-xs pt-3">
                        LKR 0.00
                    </td>
                    <td class="px-3 py-3 align-top text-center pt-2">
                        <button type="button" class="remove-row-btn text-slate-500 hover:text-rose-400 p-1 rounded-lg transition-colors" title="Remove row">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                `;

                container.appendChild(tr);

                const productSelect = tr.querySelector('.product-select');
                const productNameInput = tr.querySelector('.product-name-input');
                const qtyInput = tr.querySelector('.qty-input');
                const priceInput = tr.querySelector('.price-input');
                const discountInput = tr.querySelector('.discount-input');
                const removeBtn = tr.querySelector('.remove-row-btn');

                productSelect.addEventListener('change', function() {
                    const opt = this.options[this.selectedIndex];
                    if (opt && opt.value) {
                        const price = parseFloat(opt.getAttribute('data-price') || 0);
                        const name = opt.getAttribute('data-name') || opt.text.split(' (LKR')[0];
                        productNameInput.value = name;
                        priceInput.value = price.toFixed(2);
                    }
                    calculateTotals();
                });

                [qtyInput, priceInput, discountInput, productNameInput].forEach(inp => {
                    inp.addEventListener('input', calculateTotals);
                });

                removeBtn.addEventListener('click', function() {
                    if (container.querySelectorAll('tr.item-row').length > 1) {
                        tr.remove();
                        calculateTotals();
                    } else {
                        alert('Quotation must contain at least one product row.');
                    }
                });

                rowIdx++;
                calculateTotals();
            }

            function calculateTotals() {
                let subtotal = 0;
                const rows = container.querySelectorAll('tr.item-row');

                rows.forEach(r => {
                    const qty = parseFloat(r.querySelector('.qty-input')?.value || 0);
                    const price = parseFloat(r.querySelector('.price-input')?.value || 0);
                    const disc = parseFloat(r.querySelector('.discount-input')?.value || 0);

                    const lineTotal = Math.max(0, (qty * price) - disc);
                    const totalTd = r.querySelector('.line-total');
                    if (totalTd) {
                        totalTd.textContent = 'LKR ' + lineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                    subtotal += lineTotal;
                });

                const subtotalDisplay = document.getElementById('subtotalDisplay');
                if (subtotalDisplay) {
                    subtotalDisplay.textContent = 'LKR ' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                const overallDiscount = parseFloat(document.getElementById('discount_amount')?.value || 0);
                const taxAmount = parseFloat(document.getElementById('tax_amount')?.value || 0);

                const grandTotal = Math.max(0, (subtotal - overallDiscount) + taxAmount);
                const grandTotalDisplay = document.getElementById('grandTotalDisplay');
                if (grandTotalDisplay) {
                    grandTotalDisplay.textContent = 'LKR ' + grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }

            document.getElementById('discount_amount')?.addEventListener('input', calculateTotals);
            document.getElementById('tax_amount')?.addEventListener('input', calculateTotals);

            if (addRowBtn) {
                addRowBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    addRow();
                });
            }

            // Form validation before submit
            const form = document.getElementById('quotationForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const customerName = document.getElementById('customer_name')?.value.trim();
                    if (!customerName) {
                        e.preventDefault();
                        alert('Please enter a Customer Contact Name.');
                        document.getElementById('customer_name')?.focus();
                        return false;
                    }

                    const rows = container.querySelectorAll('tr.item-row');
                    if (rows.length === 0) {
                        e.preventDefault();
                        alert('Please add at least one product row to the quotation.');
                        return false;
                    }

                    let valid = true;
                    rows.forEach((r, idx) => {
                        const productName = r.querySelector('.product-name-input')?.value.trim();
                        const qty = parseFloat(r.querySelector('.qty-input')?.value || 0);
                        const price = parseFloat(r.querySelector('.price-input')?.value || 0);

                        if (!productName) {
                            valid = false;
                            alert(`Row #${idx + 1}: Product name is required.`);
                        } else if (isNaN(qty) || qty <= 0) {
                            valid = false;
                            alert(`Row #${idx + 1}: Quantity must be greater than 0.`);
                        } else if (isNaN(price) || price <= 0) {
                            valid = false;
                            alert(`Row #${idx + 1}: Unit price must be greater than 0.`);
                        }
                    });

                    if (!valid) {
                        e.preventDefault();
                        return false;
                    }
                });
            }

            // Render initial row
            addRow();
        });
    </script>
    @endpush
</x-admin-layout>
