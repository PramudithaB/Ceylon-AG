@if(!$selectedClient)
    <div class="flex-1 bg-white/90 backdrop-blur-xl border border-gray-100 rounded-3xl p-12 text-center flex flex-col items-center justify-center min-h-[500px] shadow-xl shadow-emerald-950/5">
        <div class="w-16 h-16 rounded-full bg-emerald-50 text-[#1E8E3E] flex items-center justify-center mb-4 border border-emerald-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">No Client Selected</h3>
        <p class="text-xs text-gray-500 font-medium max-w-sm mt-1">Select an assigned client from the sidebar on the left to load their dedicated CRM Workspace.</p>
    </div>
@else
    <div id="active-client-workspace" class="flex-1 space-y-6 animate-fadeIn">
        
        <!-- 1. CLIENT HEADER BAR -->
        <div class="ref-card rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
            <div class="flex items-center gap-5">
                <img src="{{ $selectedClient->profile_photo_url }}" alt="{{ $selectedClient->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl object-cover border-4 border-emerald-100 shadow-md shrink-0">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1 bg-emerald-50 text-[#1E8E3E] border border-emerald-200 text-[10px] font-extrabold uppercase rounded-full tracking-wider">
                            Assigned Client Workspace
                        </span>
                        @if($selectedClient->status === 'approved' || $selectedClient->status === 'active')
                            <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-extrabold uppercase rounded-full">
                                Active Account
                            </span>
                        @else
                            <span class="px-3 py-1 bg-amber-500 text-white text-[10px] font-extrabold uppercase rounded-full">
                                {{ ucfirst($selectedClient->status) }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mt-1">{{ $selectedClient->business_name ?? $selectedClient->name }}</h1>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium mt-0.5">
                        Owner: <span class="text-gray-900 font-bold">{{ $selectedClient->name }}</span> &bull; 
                        Phone: <span class="text-gray-900 font-bold">{{ $selectedClient->phone }}</span> &bull; 
                        Address: <span class="text-gray-900 font-bold">{{ $selectedClient->address ?? 'N/A' }}, {{ $selectedClient->district }}</span>
                    </p>
                </div>
            </div>

            <!-- Header Quick Meta -->
            <div class="flex items-center gap-4 border-t md:border-t-0 border-gray-100 pt-4 md:pt-0 shrink-0">
                <div class="text-right hidden sm:block">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Assigned Rep</span>
                    <span class="text-xs font-extrabold text-gray-900">{{ auth()->user()->full_name }}</span>
                    <span class="text-[10px] text-gray-400 block mt-0.5">Reg Date: {{ $selectedClient->created_at->format('M d, Y') }}</span>
                </div>

                <a href="{{ route('ref.workspace.client.print', $selectedClient->id) }}" target="_blank" 
                    class="px-4 py-2.5 bg-gray-900 hover:bg-[#1E8E3E] text-white font-extrabold text-xs rounded-2xl transition-all shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Summary</span>
                </a>
            </div>
        </div>

        <!-- QUICK ACTIONS TOOLBAR -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar">
            <a href="#section-record-payment" class="px-4 py-2 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-md hover:opacity-95 transition-all shrink-0 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Record New Payment</span>
            </a>

            <a href="#section-request-stock" class="px-4 py-2 bg-emerald-50 hover:bg-[#1E8E3E] text-[#1E8E3E] hover:text-white font-extrabold text-xs rounded-2xl border border-emerald-200 transition-all shrink-0 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Request New Stock</span>
            </a>

            <a href="#section-products" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold text-xs rounded-2xl transition-colors shrink-0">
                View Products ({{ $dashboard_cards['products_assigned'] }})
            </a>

            <a href="#section-orders" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold text-xs rounded-2xl transition-colors shrink-0">
                View Orders ({{ $financial_summary['total_orders'] }})
            </a>

            <a href="#section-payments" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold text-xs rounded-2xl transition-colors shrink-0">
                Payment History
            </a>

            <a href="#section-notes" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold text-xs rounded-2xl transition-colors shrink-0">
                Client Notes ({{ $notes->count() }})
            </a>

            <a href="#section-timeline" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold text-xs rounded-2xl transition-colors shrink-0">
                Activity Timeline
            </a>
        </div>

        <!-- 2. CLIENT DASHBOARD CARDS (9 CARDS GRID) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-9 gap-3">
            <div class="ref-card rounded-2xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Current Stock</span>
                <span class="text-lg font-extrabold text-gray-900 block mt-1">{{ number_format($dashboard_cards['current_stock']) }}</span>
            </div>

            <div class="ref-card rounded-2xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Assigned Qty</span>
                <span class="text-lg font-extrabold text-gray-900 block mt-1">{{ number_format($dashboard_cards['products_assigned']) }}</span>
            </div>

            <div class="ref-card rounded-2xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Remaining Qty</span>
                <span class="text-lg font-extrabold text-[#1E8E3E] block mt-1">{{ number_format($dashboard_cards['products_remaining']) }}</span>
            </div>

            <div class="ref-card rounded-2xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Value</span>
                <span class="text-sm font-extrabold text-gray-900 block mt-1">LKR {{ number_format($dashboard_cards['total_product_value'], 2) }}</span>
            </div>

            <div class="ref-card rounded-2xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Paid</span>
                <span class="text-sm font-extrabold text-[#1E8E3E] block mt-1">LKR {{ number_format($dashboard_cards['total_paid'], 2) }}</span>
            </div>

            <div class="ref-card rounded-2xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Outstanding</span>
                <span class="text-sm font-extrabold {{ $dashboard_cards['outstanding_balance'] > 0 ? 'text-rose-600' : 'text-gray-900' }} block mt-1">LKR {{ number_format($dashboard_cards['outstanding_balance'], 2) }}</span>
            </div>

            <div class="ref-card rounded-2xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Today's Collection</span>
                <span class="text-sm font-extrabold text-emerald-700 block mt-1">LKR {{ number_format($dashboard_cards['todays_collection'], 2) }}</span>
            </div>

            <div class="ref-card rounded-2xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Last Payment</span>
                <span class="text-sm font-extrabold text-gray-900 block mt-1">LKR {{ number_format($dashboard_cards['last_payment'], 2) }}</span>
            </div>

            <div class="ref-card rounded-2xl p-4 col-span-2 sm:col-span-1">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Last Order</span>
                <span class="text-xs font-extrabold text-gray-900 block mt-1">{{ $dashboard_cards['last_order_date'] }}</span>
            </div>
        </div>

        <!-- 3. PAYMENT & FINANCIAL SETTLEMENT SUMMARY -->
        <div class="ref-card rounded-3xl p-6 sm:p-8 space-y-4 bg-gradient-to-r from-emerald-900/5 via-white to-white">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Client Financial Settlement Summary</h2>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Real-time payment audit summary for {{ $selectedClient->business_name ?? $selectedClient->name }}</p>
                </div>
                <span class="px-3.5 py-1 bg-emerald-50 border border-emerald-200 text-[#1E8E3E] font-extrabold text-xs rounded-full">
                    {{ $financial_summary['payment_status'] }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Amount Given</span>
                    <span class="text-lg font-extrabold text-gray-900 mt-0.5 block">LKR {{ number_format($financial_summary['total_given'], 2) }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Amount Paid</span>
                    <span class="text-lg font-extrabold text-[#1E8E3E] mt-0.5 block">LKR {{ number_format($financial_summary['total_paid'], 2) }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Outstanding Balance</span>
                    <span class="text-lg font-extrabold {{ $financial_summary['outstanding_balance'] > 0 ? 'text-rose-600' : 'text-gray-900' }} mt-0.5 block">LKR {{ number_format($financial_summary['outstanding_balance'], 2) }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Payment Progress</span>
                    <span class="text-lg font-extrabold text-[#1E8E3E] mt-0.5 block">{{ $financial_summary['payment_progress'] }}%</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] h-full rounded-full transition-all duration-500" style="width: {{ $financial_summary['payment_progress'] }}%"></div>
            </div>
        </div>

        <!-- 3.5 SALES SUMMARY SECTION -->
        <div class="ref-card rounded-3xl p-6 sm:p-8 space-y-4">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Client Sales Performance Summary</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Sales turnover, item volume, and remaining stock for {{ $selectedClient->business_name ?? $selectedClient->name }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div class="p-4 rounded-2xl bg-gray-50/80 border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Today's Sales</span>
                    <span class="text-base font-extrabold text-gray-900 mt-1 block">LKR {{ number_format($sales_summary['todays_sales'] ?? 0, 2) }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-gray-50/80 border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Monthly Sales</span>
                    <span class="text-base font-extrabold text-gray-900 mt-1 block">LKR {{ number_format($sales_summary['monthly_sales'] ?? 0, 2) }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-gray-50/80 border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Sales</span>
                    <span class="text-base font-extrabold text-[#1E8E3E] mt-1 block">LKR {{ number_format($sales_summary['total_sales'] ?? 0, 2) }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-gray-50/80 border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Products Sold</span>
                    <span class="text-base font-extrabold text-gray-900 mt-1 block">{{ number_format($sales_summary['products_sold'] ?? 0) }} units</span>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100 col-span-2 sm:col-span-1">
                    <span class="text-[10px] font-bold text-[#1E8E3E] uppercase tracking-wider block">Remaining Stock</span>
                    <span class="text-base font-extrabold text-[#1E8E3E] mt-1 block">{{ number_format($sales_summary['remaining_stock'] ?? 0) }} units</span>
                </div>
            </div>
        </div>

        <!-- 4. PRODUCT STOCK SECTION -->
        <div id="section-products" class="ref-card rounded-3xl p-6 sm:p-8 space-y-4">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Assigned Product Stock Inventory</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Inventory levels, sold volume, and remaining stock balances</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium text-gray-700">
                    <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3.5">Product</th>
                            <th class="px-5 py-3.5 text-center">Assigned Qty</th>
                            <th class="px-5 py-3.5 text-center">Sold Qty</th>
                            <th class="px-5 py-3.5 text-center">Remaining Qty</th>
                            <th class="px-5 py-3.5">Dealer Price</th>
                            <th class="px-5 py-3.5">Selling Price</th>
                            <th class="px-5 py-3.5">Stock Value</th>
                            <th class="px-5 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($product_stock as $ps)
                            <tr class="hover:bg-emerald-50/30 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] flex items-center justify-center text-white font-extrabold text-xs shadow-sm">
                                            {{ strtoupper(substr($ps->product->name ?? 'P', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-gray-900 text-sm">{{ $ps->product->name ?? 'Product' }}</div>
                                            <div class="text-[10px] text-gray-400 font-semibold">SKU: {{ $ps->product->sku ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-gray-900">{{ number_format($ps->assigned_qty) }}</td>
                                <td class="px-5 py-4 text-center font-bold text-gray-500">{{ number_format($ps->sold_qty) }}</td>
                                <td class="px-5 py-4 text-center font-extrabold text-[#1E8E3E] text-sm">{{ number_format($ps->remaining_qty) }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">LKR {{ number_format($ps->dealer_price, 2) }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">LKR {{ number_format($ps->selling_price, 2) }}</td>
                                <td class="px-5 py-4 font-extrabold text-[#1E8E3E]">LKR {{ number_format($ps->stock_value, 2) }}</td>
                                <td class="px-5 py-4">
                                    @if($ps->status === 'In Stock')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-[#1E8E3E] border border-emerald-200 text-[10px] font-extrabold rounded-full">
                                            In Stock
                                        </span>
                                    @elseif($ps->status === 'Low Stock')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold rounded-full">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 border border-rose-200 text-[10px] font-extrabold rounded-full">
                                            Out of Stock
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-gray-400 font-medium">No assigned products for this client.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 5. ORDER HISTORY SECTION -->
        <div id="section-orders" class="ref-card rounded-3xl p-6 sm:p-8 space-y-4">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Complete Order & Quotation History</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Transactions, sales orders, and issued quotations</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium text-gray-700">
                    <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3.5">Type</th>
                            <th class="px-5 py-3.5">Order / Qtn #</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5">Assigned By</th>
                            <th class="px-5 py-3.5">Products Description</th>
                            <th class="px-5 py-3.5 text-center">Quantity</th>
                            <th class="px-5 py-3.5">Total Amount</th>
                            <th class="px-5 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($order_history as $oh)
                            <tr class="hover:bg-emerald-50/30 transition-colors">
                                <td class="px-5 py-4 font-bold text-gray-900">{{ $oh->type }}</td>
                                <td class="px-5 py-4 font-extrabold text-[#1E8E3E]">{{ $oh->order_number }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ \Carbon\Carbon::parse($oh->date)->format('M d, Y') }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">{{ $oh->assigned_by }}</td>
                                <td class="px-5 py-4 font-medium text-gray-700">{{ $oh->products }}</td>
                                <td class="px-5 py-4 text-center font-bold text-gray-900">{{ number_format($oh->quantity) }}</td>
                                <td class="px-5 py-4 font-extrabold text-[#1E8E3E]">LKR {{ number_format($oh->amount, 2) }}</td>
                                <td class="px-5 py-4 font-extrabold text-xs">{{ $oh->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-gray-400 font-medium">No orders recorded for this client.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 6. RECORD NEW PAYMENT CARD & FORM -->
        <div id="section-record-payment" class="ref-card rounded-3xl p-6 sm:p-8 space-y-6 border-2 border-emerald-200/60 shadow-xl shadow-emerald-900/5">
            <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-[#1E8E3E] uppercase tracking-wider block">Financial Audit Entry</span>
                    <h2 class="text-lg font-extrabold text-gray-900 tracking-tight mt-0.5">Record New Payment Collection</h2>
                </div>
                <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-[#1E8E3E] text-xs font-extrabold rounded-full">
                    Auto Client: {{ $selectedClient->business_name ?? $selectedClient->name }}
                </span>
            </div>

            <form method="POST" action="{{ route('ref.workspace.payments.store', $selectedClient->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Payment Method -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Payment Method *</label>
                        <select name="payment_method" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                            <option value="cash">Cash Collection</option>
                            <option value="bank_transfer" selected>Bank Transfer</option>
                            <option value="online_transfer">Online Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Amount Paid (LKR) *</label>
                        <input type="number" step="0.01" min="0.01" name="amount" required placeholder="Enter amount" class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Payment Date *</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <!-- Reference Number -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Reference Number</label>
                        <input type="text" name="reference_number" placeholder="Bank ref / receipt #..." class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>

                    <!-- Bank Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Bank Name</label>
                        <input type="text" name="bank_name" placeholder="Commercial Bank, Sampath..." class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>

                    <!-- Cheque Number -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cheque Number</label>
                        <input type="text" name="cheque_number" placeholder="For cheque payments..." class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>

                    <!-- Card Last 4 -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Card Last 4 Digits</label>
                        <input type="text" maxlength="4" name="card_last_four" placeholder="e.g. 4321" class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Receipt File -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Receipt / Bank Slip (Image or PDF)</label>
                        <input type="file" name="payment_screenshot" accept="image/*,.pdf" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-emerald-50 file:text-[#1E8E3E] hover:file:bg-emerald-100">
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Remarks / Notes</label>
                        <input type="text" name="remarks" placeholder="Optional collection note..." class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-400 font-medium">Logged Representative: <strong class="text-gray-900">{{ auth()->user()->full_name }}</strong></span>

                    <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-lg shadow-emerald-700/25 hover:opacity-95 transition-all">
                        Save Payment (Submit for Admin Verification)
                    </button>
                </div>
            </form>
        </div>

        <!-- 7. PAYMENT HISTORY SECTION -->
        <div id="section-payments" class="ref-card rounded-3xl p-6 sm:p-8 space-y-4">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Payment Audit Log & History</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Recorded payment deposits and verification status from Admin</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium text-gray-700">
                    <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3.5">Payment #</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5">Method</th>
                            <th class="px-5 py-3.5">Bank / Ref</th>
                            <th class="px-5 py-3.5">Amount</th>
                            <th class="px-5 py-3.5">Collected By</th>
                            <th class="px-5 py-3.5">Verified By</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($payment_history as $pay)
                            <tr class="hover:bg-emerald-50/30 transition-colors">
                                <td class="px-5 py-4 font-bold text-[#1E8E3E]">{{ $pay->payment_number }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900 uppercase text-[11px]">{{ str_replace('_', ' ', $pay->payment_method ?? 'Bank Transfer') }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-gray-900">{{ $pay->bank_name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium">Ref: {{ $pay->reference_number }}</div>
                                </td>
                                <td class="px-5 py-4 font-extrabold text-[#1E8E3E] text-sm">LKR {{ number_format((float)$pay->amount, 2) }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">{{ $pay->collector->full_name ?? 'Ref' }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">{{ $pay->reviewer->full_name ?? 'Pending' }}</td>
                                <td class="px-5 py-4">
                                    @if($pay->status === 'approved')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-[#1E8E3E] border border-emerald-200 text-[10px] font-extrabold rounded-full">
                                            Verified
                                        </span>
                                    @elseif($pay->status === 'pending')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold rounded-full">
                                            Pending Verification
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 border border-rose-200 text-[10px] font-extrabold rounded-full">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($pay->payment_screenshot)
                                        <a href="{{ Storage::url($pay->payment_screenshot) }}" target="_blank" class="px-3 py-1 bg-emerald-50 hover:bg-[#1E8E3E] text-[#1E8E3E] hover:text-white font-extrabold text-[11px] rounded-xl transition-all inline-block">
                                            View Slip
                                        </a>
                                    @else
                                        <span class="text-gray-400 font-normal">None</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-8 text-center text-gray-400 font-medium">No payments recorded for this client.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 8. REQUEST NEW STOCK CARD & FORM -->
        <div id="section-request-stock" class="ref-card rounded-3xl p-6 sm:p-8 space-y-6 border border-gray-100">
            <div class="border-b border-gray-100 pb-3">
                <span class="text-[10px] font-extrabold text-[#1E8E3E] uppercase tracking-wider block">Inventory Re-allocation</span>
                <h2 class="text-lg font-extrabold text-gray-900 tracking-tight mt-0.5">Request New Product Stock from Admin</h2>
            </div>

            <form method="POST" action="{{ route('ref.workspace.stock-requests.store', $selectedClient->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <!-- Product -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Select Product *</label>
                        <select name="product_id" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                            <option value="" disabled selected>Choose product item</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} (Available: {{ $prod->stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Quantity *</label>
                        <input type="number" min="1" value="1" name="requested_quantity" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Priority Level</label>
                        <select name="priority" class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                            <option value="low">Low Priority</option>
                            <option value="medium" selected>Medium Priority</option>
                            <option value="high">High Priority</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Delivery Date -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Expected Delivery Date</label>
                        <input type="date" name="expected_delivery_date" class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>

                    <!-- Reason -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Stock Request Reason & Justification</label>
                        <input type="text" name="reason" placeholder="Client urgent re-order demand..." class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-gray-900 hover:bg-[#1E8E3E] text-white font-extrabold text-xs rounded-2xl shadow-md transition-all">
                        Submit Stock Request to Admin
                    </button>
                </div>
            </form>
        </div>

        <!-- 9. CLIENT NOTES LOG & FORM -->
        <div id="section-notes" class="ref-card rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Client Relationship Notes Log</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Record client conversations, payment delays, or custom requests</p>
            </div>

            <!-- Note Input Form -->
            <form method="POST" action="{{ route('ref.workspace.notes.store', $selectedClient->id) }}" class="space-y-3">
                @csrf
                <textarea name="content" rows="3" required placeholder="Write a note about this client (e.g. Customer requested additional stock, Payment promised next week)..." class="auth-input block w-full p-4 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white"></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-md hover:opacity-95 transition-all">
                        Add Note
                    </button>
                </div>
            </form>

            <!-- Notes List -->
            <div class="space-y-3 pt-2">
                @forelse($notes as $n)
                    <div class="p-4 rounded-2xl bg-gray-50/80 border border-gray-100 text-xs space-y-1">
                        <div class="flex items-center justify-between text-gray-400 font-medium text-[11px]">
                            <span class="font-extrabold text-gray-900">{{ $n->refUser->full_name ?? 'Sales Rep' }}</span>
                            <span>{{ $n->created_at->format('M d, Y H:i A') }}</span>
                        </div>
                        <p class="text-gray-700 font-medium leading-relaxed">{{ $n->content }}</p>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-gray-400 font-medium">No notes recorded yet for this client.</div>
                @endforelse
            </div>
        </div>

        <!-- 10. CLIENT ACTIVITY TIMELINE -->
        <div id="section-timeline" class="ref-card rounded-3xl p-6 sm:p-8 space-y-6">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Client Activity & Operational Timeline</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Chronological audit stream of registrations, orders, payments, and admin actions</p>
            </div>

            <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                @forelse($timeline as $event)
                    <div class="relative flex items-start gap-4">
                        <div class="absolute -left-6 top-1 w-5 h-5 rounded-full bg-white border-2 border-[#1E8E3E] flex items-center justify-center text-[#1E8E3E]">
                            <div class="w-2 h-2 rounded-full bg-[#1E8E3E]"></div>
                        </div>

                        <div class="bg-gray-50/80 p-4 rounded-2xl border border-gray-100 flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-extrabold text-gray-900">{{ $event->title }}</h3>
                                <span class="text-[10px] font-semibold text-gray-400">{{ \Carbon\Carbon::parse($event->timestamp)->format('M d, Y H:i') }}</span>
                            </div>
                            <p class="text-xs text-gray-600 font-medium mt-1 leading-relaxed">{{ $event->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-gray-400 font-medium">No activity events recorded yet.</div>
                @endforelse
            </div>
        </div>

    </div>
@endif
