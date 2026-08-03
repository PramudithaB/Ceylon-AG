<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Master Sales Reports & Analytics</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">High-level sales turnover and client distribution analytics</p>
            </div>
            <a href="{{ route('admin.sales.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Sales List
            </a>
        </div>
    </x-slot>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-6">
        <!-- Filter Bar -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.sales.reports') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
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
                    <label for="product_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Product</label>
                    <select id="product_id" name="product_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                        <option value="all" {{ ($filters['product_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Products</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ ($filters['product_id'] ?? '') == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
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
                        Generate Report
                    </button>
                    <a href="{{ route('admin.sales.reports') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <div class="text-xs font-medium text-slate-400">Total Units Sold Globally</div>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $reportData['total_units'] }} units</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <div class="text-xs font-medium text-emerald-500">Master Retail Revenue</div>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">LKR {{ number_format($reportData['total_revenue'], 2) }}</div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <div class="text-xs font-medium text-teal-500">Recorded Sales Transactions</div>
                <div class="text-2xl font-black text-teal-600 dark:text-teal-400 mt-1">{{ $reportData['total_transactions'] }} transactions</div>
            </div>
        </div>

        <!-- Master Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Line Chart: Sales Trend -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Global Revenue Trend</h3>
                <div class="h-64 relative">
                    <canvas id="adminSalesTrendChart"></canvas>
                </div>
            </div>

            <!-- Bar Chart: Top Products -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Top 5 Best-Selling Products</h3>
                <div class="h-64 relative">
                    <canvas id="adminTopProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Master Sales Trend Line Chart
            const trendData = @json($reportData['sales_trend']);
            const trendLabels = trendData.map(item => item.date);
            const trendRevenue = trendData.map(item => item.total_revenue);

            const ctxTrend = document.getElementById('adminSalesTrendChart').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: trendLabels.length ? trendLabels : ['No Data'],
                    datasets: [{
                        label: 'Revenue (LKR)',
                        data: trendRevenue.length ? trendRevenue : [0],
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });

            // 2. Top Products Bar Chart
            const topProducts = @json($reportData['top_products']);
            const topLabels = topProducts.map(i => i.product ? i.product.name : 'Unknown');
            const topUnits = topProducts.map(i => i.units_sold);

            const ctxBar = document.getElementById('adminTopProductsChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: topLabels.length ? topLabels : ['No Data'],
                    datasets: [{
                        label: 'Units Sold',
                        data: topUnits.length ? topUnits : [0],
                        backgroundColor: '#0d9488'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    </script>
</x-admin-layout>
