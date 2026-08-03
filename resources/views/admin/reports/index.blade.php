<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Master Financial & Inventory Reports</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">High-level financial turnover, client balances, stock metrics, and automated exports</p>
            </div>

            <!-- Export Buttons Toolbar -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.reports.export-csv', request()->all()) }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 transition-all">
                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </a>

                <a href="{{ route('admin.reports.export-excel', request()->all()) }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 transition-all border border-emerald-200 dark:border-emerald-800">
                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>

                <a href="{{ route('admin.reports.export-pdf', request()->all()) }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-6">

        <!-- Date Range & Client Filter -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label for="client_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Client Partner</label>
                    <select id="client_id" name="client_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                        <option value="all" {{ ($filters['client_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ ($filters['client_id'] ?? '') == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->business_name }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                </div>

                <div>
                    <label for="end_date" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl text-xs font-semibold bg-emerald-600 text-white text-center">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Dashboard Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Sales Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-medium text-slate-400">Total Retail Sales</div>
                    <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5">LKR {{ number_format($summary['total_sales'], 2) }}</div>
                </div>
            </div>

            <!-- Total Approved Payments Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950 text-teal-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-medium text-slate-400">Approved Payments</div>
                    <div class="text-xl font-black text-teal-600 dark:text-teal-400 mt-0.5">LKR {{ number_format($summary['total_payments'], 2) }}</div>
                </div>
            </div>

            <!-- Outstanding Balance Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-medium text-slate-400">Outstanding Balance</div>
                    <div class="text-xl font-black text-amber-600 dark:text-amber-400 mt-0.5">LKR {{ number_format($summary['outstanding_balance'], 2) }}</div>
                </div>
            </div>

            <!-- Remaining Stock Units Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-medium text-slate-400">Remaining Stock</div>
                    <div class="text-xl font-black text-indigo-600 dark:text-indigo-400 mt-0.5">{{ $summary['remaining_stock'] }} units</div>
                </div>
            </div>
        </div>

        <!-- Master Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Monthly Sales vs Monthly Payments Trend -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Monthly Sales Revenue vs Approved Payments (LKR)</h3>
                <div class="h-64 relative">
                    <canvas id="salesPaymentsTrendChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Top 5 Client Partners Ranking -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Top 5 Client Partners by Sales Volume (LKR)</h3>
                <div class="h-64 relative">
                    <canvas id="topClientsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Clients Ranking Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Top Client Partners Performance</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Rank</th>
                            <th class="py-3.5 px-6">Client Partner</th>
                            <th class="py-3.5 px-6">Business Name</th>
                            <th class="py-3.5 px-6">Total Units Sold</th>
                            <th class="py-3.5 px-6 text-right">Total Revenue (LKR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($topClients as $index => $clientItem)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-4 px-6 font-bold text-emerald-600">
                                    #{{ $index + 1 }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-900 dark:text-white">
                                    {{ $clientItem['name'] }}
                                </td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-400">
                                    {{ $clientItem['business_name'] }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800 dark:text-slate-200">
                                    {{ $clientItem['total_units'] }} units
                                </td>
                                <td class="py-4 px-6 text-right font-black text-emerald-600 dark:text-emerald-400">
                                    LKR {{ number_format($clientItem['total_revenue'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    No client sales records aggregated yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Chart.js Setup Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Monthly Sales vs Payments Dual Chart
            const salesData = @json($monthlySales);
            const paymentsData = @json($monthlyPayments);

            const months = Array.from(new Set([...salesData.map(i => i.month), ...paymentsData.map(i => i.month)])).sort();
            
            const salesValues = months.map(m => {
                const found = salesData.find(i => i.month === m);
                return found ? parseFloat(found.total_sales) : 0;
            });

            const paymentValues = months.map(m => {
                const found = paymentsData.find(i => i.month === m);
                return found ? parseFloat(found.total_payments) : 0;
            });

            const ctxTrend = document.getElementById('salesPaymentsTrendChart').getContext('2d');
            new Chart(ctxTrend, {
                type: 'bar',
                data: {
                    labels: months.length ? months : ['No Data'],
                    datasets: [
                        { label: 'Sales Revenue (LKR)', data: salesValues.length ? salesValues : [0], backgroundColor: '#059669' },
                        { label: 'Approved Payments (LKR)', data: paymentValues.length ? paymentValues : [0], backgroundColor: '#0d9488' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });

            // 2. Top Clients Horizontal Bar Chart
            const topClients = @json($topClients);
            const topLabels = topClients.map(i => i.name + ' (' + i.business_name + ')');
            const topRevenues = topClients.map(i => i.total_revenue);

            const ctxTop = document.getElementById('topClientsChart').getContext('2d');
            new Chart(ctxTop, {
                type: 'bar',
                data: {
                    labels: topLabels.length ? topLabels : ['No Data'],
                    datasets: [{
                        label: 'Revenue (LKR)',
                        data: topRevenues.length ? topRevenues : [0],
                        backgroundColor: '#6366f1'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { x: { beginAtZero: true } }
                }
            });
        });
    </script>
</x-admin-layout>
