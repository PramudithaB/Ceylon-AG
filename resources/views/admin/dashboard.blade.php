<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Admin Control Center</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Enterprise operational overview and live performance metrics</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.quotations.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Quotation
                </a>
                <a href="{{ route('admin.product-assignments.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-white border border-slate-700 transition-all">
                    Assign Stock
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- 1. Executive Metric KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Metric 1: Total Revenue -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Sales Revenue</span>
                    <span class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                        {{ format_currency($metrics['total_revenue']) }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                        @if($metrics['total_sales_count'] > 0)
                            {{ $metrics['total_sales_count'] }} recorded sales
                        @else
                            No sales records yet
                        @endif
                    </p>
                </div>
            </div>

            <!-- Metric 2: Verified Payments -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Approved Payments</span>
                    <span class="p-2 rounded-xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                        {{ format_currency($metrics['verified_payments_amount']) }}
                    </h3>
                    <p class="text-xs font-medium mt-1 {{ $metrics['pending_payments_count'] > 0 ? 'text-amber-600 dark:text-amber-400 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                        @if($metrics['pending_payments_count'] > 0)
                            {{ $metrics['pending_payments_count'] }} awaiting review
                        @else
                            No pending payments
                        @endif
                    </p>
                </div>
            </div>

            <!-- Metric 3: Active Client Network -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Client Network</span>
                    <span class="p-2 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                        {{ $metrics['active_clients_count'] }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                        {{ $metrics['active_refs_count'] }} Active Reps &bull; {{ $metrics['pending_clients_count'] }} Pending Approvals
                    </p>
                </div>
            </div>

            <!-- Metric 4: Inventory & Warehouse Stock -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Warehouse Stock</span>
                    <span class="p-2 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                        {{ number_format($metrics['total_stock_on_hand']) }} <span class="text-sm font-semibold text-slate-400">units</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                        {{ $metrics['active_products_count'] }} Products &bull; {{ $metrics['pending_stock_requests_count'] }} Pending Requests
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Dual Operational Review Cards: Stock Requests & Payments -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Pending Stock Requests Review -->
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-black text-slate-900 dark:text-white">Pending Stock Requests</h2>
                            <p class="text-xs text-slate-500">Client allocation requests requiring approval</p>
                        </div>
                        <a href="{{ route('admin.stock-requests.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                            View All ({{ $metrics['pending_stock_requests_count'] }}) &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($pendingStockRequests as $req)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <div class="min-w-0 pr-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-bold text-emerald-600">{{ $req->request_number }}</span>
                                        <span class="text-xs font-black text-slate-900 dark:text-white truncate">
                                            {{ $req->client->business_name ?? $req->client->name }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $req->product->name ?? 'Product' }} &bull; <strong class="text-slate-700 dark:text-slate-300">{{ $req->requested_quantity }} units</strong>
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <a href="{{ route('admin.stock-requests.index') }}" class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 hover:bg-emerald-100 transition-colors">
                                        Review
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center text-xs text-slate-400 dark:text-slate-500 space-y-1">
                                <svg class="w-8 h-8 text-slate-300 dark:text-slate-700 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">No stock requests found</p>
                                <p class="text-[11px]">All submitted client requests have been processed.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-3 bg-slate-50/60 dark:bg-slate-950/30 border-t border-slate-100 dark:border-slate-800 text-center">
                    <a href="{{ route('admin.stock-requests.index') }}" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-emerald-600">
                        Manage all stock requests
                    </a>
                </div>
            </div>

            <!-- Pending Payments Review -->
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-black text-slate-900 dark:text-white">Pending Payment Approvals</h2>
                            <p class="text-xs text-slate-500">Bank transfers and slips awaiting verification</p>
                        </div>
                        <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                            View All ({{ $metrics['pending_payments_count'] }}) &rarr;
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($pendingPayments as $pay)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <div class="min-w-0 pr-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-bold text-sky-600">{{ $pay->payment_number }}</span>
                                        <span class="text-xs font-black text-slate-900 dark:text-white truncate">
                                            {{ $pay->client->business_name ?? $pay->client->name }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ ucfirst(str_replace('_', ' ', $pay->payment_method ?? 'Bank')) }} &bull; <strong class="text-emerald-600 dark:text-emerald-400">{{ format_currency($pay->amount) }}</strong>
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <a href="{{ route('admin.payments.show', $pay->id) }}" class="px-3 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 hover:bg-sky-100 transition-colors">
                                        Verify
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center text-xs text-slate-400 dark:text-slate-500 space-y-1">
                                <svg class="w-8 h-8 text-slate-300 dark:text-slate-700 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="font-semibold text-slate-600 dark:text-slate-400">No payments found</p>
                                <p class="text-[11px]">All customer payments are currently verified and up to date.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-3 bg-slate-50/60 dark:bg-slate-950/30 border-t border-slate-100 dark:border-slate-800 text-center">
                    <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-sky-600">
                        Manage all payment records
                    </a>
                </div>
            </div>

        </div>

        <!-- 3. Recent Sales Transactions Table -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black text-slate-900 dark:text-white">Recent Sales Transactions</h2>
                    <p class="text-xs text-slate-500">Live feed of client retail and wholesale sales</p>
                </div>
                <a href="{{ route('admin.sales.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    View Sales Ledger &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-950/50 border-b border-slate-200/80 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-6">Sale #</th>
                            <th class="py-3 px-6">Client / Business</th>
                            <th class="py-3 px-6">Product</th>
                            <th class="py-3 px-6 text-center">Quantity</th>
                            <th class="py-3 px-6 text-right">Total Amount</th>
                            <th class="py-3 px-6">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="py-3.5 px-6 font-mono font-bold text-emerald-600">
                                    {{ $sale->sale_number }}
                                </td>
                                <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white">
                                    {{ $sale->client->business_name ?? $sale->client->name }}
                                </td>
                                <td class="py-3.5 px-6 text-slate-700 dark:text-slate-300">
                                    {{ $sale->product->name ?? 'Product' }}
                                </td>
                                <td class="py-3.5 px-6 text-center font-bold text-slate-900 dark:text-white">
                                    {{ $sale->quantity }}
                                </td>
                                <td class="py-3.5 px-6 text-right font-black text-emerald-600 dark:text-emerald-400">
                                    {{ format_currency($sale->total_amount) }}
                                </td>
                                <td class="py-3.5 px-6 text-slate-500">
                                    {{ $sale->sold_at ? $sale->sold_at->format('M d, Y') : $sale->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400 dark:text-slate-500">
                                    <p class="font-semibold text-slate-600 dark:text-slate-400">No sales records yet</p>
                                    <p class="text-[11px] mt-0.5">Recorded client sales will appear in this ledger automatically.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Recent Quotations Table -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black text-slate-900 dark:text-white">Recent Quotations</h2>
                    <p class="text-xs text-slate-500">Issued commercial proposals and estimations</p>
                </div>
                <a href="{{ route('admin.quotations.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    View All Quotations &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-950/50 border-b border-slate-200/80 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-6">Quotation #</th>
                            <th class="py-3 px-6">Customer / Client</th>
                            <th class="py-3 px-6">District</th>
                            <th class="py-3 px-6 text-right">Grand Total</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6">Date</th>
                            <th class="py-3 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($recentQuotations as $quotation)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="py-3.5 px-6 font-mono font-bold text-emerald-600">
                                    {{ $quotation->quotation_number }}
                                </td>
                                <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white">
                                    {{ $quotation->customer_name }}
                                    @if($quotation->business_name)
                                        <span class="block text-[10px] text-slate-400 font-normal">{{ $quotation->business_name }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6 text-slate-500">
                                    {{ $quotation->district ?? 'N/A' }}
                                </td>
                                <td class="py-3.5 px-6 text-right font-black text-slate-900 dark:text-white">
                                    {{ format_currency($quotation->grand_total) }}
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        @if($quotation->status === 'accepted') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif($quotation->status === 'rejected') bg-rose-50 text-rose-700 border border-rose-200
                                        @elseif($quotation->status === 'sent') bg-sky-50 text-sky-700 border border-sky-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                        {{ $quotation->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-slate-500">
                                    {{ $quotation->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <a href="{{ route('admin.quotations.show', $quotation->id) }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-400 dark:text-slate-500">
                                    <p class="font-semibold text-slate-600 dark:text-slate-400">No quotations generated yet</p>
                                    <p class="text-[11px] mt-0.5">Click <a href="{{ route('admin.quotations.create') }}" class="text-emerald-600 underline font-bold">New Quotation</a> to draft an official quotation.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
