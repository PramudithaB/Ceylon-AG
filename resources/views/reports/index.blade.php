<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    Partner Sales & Financial Analytics
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">View sales turnover, verified bank payments, remaining stock, and download reports</p>
            </div>

            <!-- Export Toolbar -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('reports.export-csv', request()->all()) }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 transition-all">
                    Export CSV
                </a>

                <a href="{{ route('reports.export-excel', request()->all()) }}" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-semibold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 transition-all border border-emerald-200 dark:border-emerald-800">
                    Export Excel
                </a>

                <a href="{{ route('reports.export-pdf', request()->all()) }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Date Range Filter -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                            Filter Date
                        </button>
                        <a href="{{ route('reports.index') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                    <div class="text-xs font-medium text-slate-400">Total Recorded Sales</div>
                    <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">LKR {{ number_format($summary['total_sales'], 2) }}</div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                    <div class="text-xs font-medium text-slate-400">Approved Payments</div>
                    <div class="text-xl font-black text-teal-600 dark:text-teal-400 mt-1">LKR {{ number_format($summary['total_payments'], 2) }}</div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                    <div class="text-xs font-medium text-slate-400">Outstanding Balance</div>
                    <div class="text-xl font-black text-amber-600 dark:text-amber-400 mt-1">LKR {{ number_format($summary['outstanding_balance'], 2) }}</div>
                </div>

                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                    <div class="text-xs font-medium text-slate-400">Remaining Stock</div>
                    <div class="text-xl font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ $summary['remaining_stock'] }} units</div>
                </div>
            </div>

            <!-- Visual Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sales vs Payments Bar Chart -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Monthly Sales Revenue vs Verified Payments (LKR)</h3>
                    <div class="h-64 relative">
                        <canvas id="clientSalesPaymentsChart"></canvas>
                    </div>
                </div>

                <!-- Product Inventory Breakdown Chart -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Product Inventory Stock Ratio</h3>
                    <div class="h-64 relative">
                        <canvas id="clientInventoryChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            const ctxTrend = document.getElementById('clientSalesPaymentsChart').getContext('2d');
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

            // Inventory Chart
            const breakdown = @json($inventoryBreakdown);
            const prodNames = breakdown.map(i => i.name);
            const soldQtys = breakdown.map(i => i.sold_qty);
            const remainQtys = breakdown.map(i => i.remaining_qty);

            const ctxInv = document.getElementById('clientInventoryChart').getContext('2d');
            new Chart(ctxInv, {
                type: 'bar',
                data: {
                    labels: prodNames.length ? prodNames : ['No Products'],
                    datasets: [
                        { label: 'Sold Units', data: soldQtys, backgroundColor: '#0d9488' },
                        { label: 'Remaining Stock', data: remainQtys, backgroundColor: '#6366f1' }
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
