<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    Client Sales Reports & Analytics
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Visual charts and inventory turnover analytics</p>
            </div>
            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Sales History
            </a>
        </div>
    </x-slot>

    <!-- Include Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Bar -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <form method="GET" action="{{ route('sales.reports') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="product_id" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Product</label>
                        <select id="product_id" name="product_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2 px-3">
                            <option value="all" {{ ($filters['product_id'] ?? 'all') === 'all' ? 'selected' : '' }}>All Assigned Products</option>
                            @foreach($assignedProducts as $prod)
                                <option value="{{ $prod['product_id'] }}" {{ ($filters['product_id'] ?? '') == $prod['product_id'] ? 'selected' : '' }}>{{ $prod['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Start Date</label>
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
                        <a href="{{ route('sales.reports') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Numbers -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                    <div class="text-xs font-medium text-slate-400">Total Sales Volume</div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $reportData['total_units'] }} units</div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                    <div class="text-xs font-medium text-emerald-500">Total Revenue Generated</div>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">LKR {{ number_format($reportData['total_revenue'], 2) }}</div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                    <div class="text-xs font-medium text-teal-500">Total Recorded Transactions</div>
                    <div class="text-2xl font-black text-teal-600 dark:text-teal-400 mt-1">{{ $reportData['total_transactions'] }} sales</div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Chart 1: Sales Trend -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Sales Revenue Trend over Time</h3>
                    <div class="h-64 relative">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Inventory Breakdown (Assigned vs Sold) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Assigned Stock vs Units Sold per Product</h3>
                    <div class="h-64 relative">
                        <canvas id="inventoryBarChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Script Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Sales Trend Line Chart
            const trendData = @json($reportData['sales_trend']);
            const trendLabels = trendData.map(item => item.date);
            const trendRevenue = trendData.map(item => item.total_revenue);

            const ctxTrend = document.getElementById('salesTrendChart').getContext('2d');
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
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // 2. Inventory Breakdown Bar Chart
            const assignedProducts = @json($assignedProducts);
            const productNames = assignedProducts.map(item => item.name);
            const assignedQtys = assignedProducts.map(item => item.assigned_qty);
            const soldQtys = assignedProducts.map(item => item.sold_qty);

            const ctxBar = document.getElementById('inventoryBarChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: productNames.length ? productNames : ['No Products'],
                    datasets: [
                        {
                            label: 'Assigned Units',
                            data: assignedQtys.length ? assignedQtys : [0],
                            backgroundColor: '#94a3b8'
                        },
                        {
                            label: 'Sold Units',
                            data: soldQtys.length ? soldQtys : [0],
                            backgroundColor: '#059669'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    </script>
</x-app-layout>
