<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    Client Inventory & Sales Dashboard
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Welcome back, <span class="font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</span> ({{ auth()->user()->business_name ?? 'Ceylon AG Partner' }})
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('sales.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Record Product Sale
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-6 space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Metric Cards (Total Assigned, Total Sold, Remaining Stock) -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- Total Assigned -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-400">Total Assigned</div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $summary['total_assigned'] }} units</div>
                    </div>
                </div>

                <!-- Total Sold -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950 text-teal-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-400">Total Sold</div>
                        <div class="text-2xl font-black text-teal-600 dark:text-teal-400">{{ $summary['total_sold'] }} units</div>
                    </div>
                </div>

                <!-- Remaining Stock -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-400">Remaining Stock</div>
                        <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $summary['remaining_stock'] }} units</div>
                    </div>
                </div>

                <!-- Sales Revenue -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-slate-400">Recorded Revenue</div>
                        <div class="text-lg font-black text-emerald-600 dark:text-emerald-400">LKR {{ number_format($summary['total_revenue'], 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Charts Visual Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Doughnut Chart: Overall Stock Status -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Stock Status Ratio</h3>
                    <div class="h-60 relative flex items-center justify-center">
                        <canvas id="stockDoughnutChart"></canvas>
                    </div>
                </div>

                <!-- Bar Chart: Per-Product Inventory Breakdown -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Product Inventory Breakdown (Assigned vs Sold vs Remaining)</h3>
                    <div class="h-60 relative">
                        <canvas id="inventoryBarChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Assigned Inventory Table -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Your Assigned Products & Live Inventory</h3>
                        <p class="text-xs text-slate-500">Track assigned quantities, recorded sales, and current remaining stock</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                                <th class="py-3.5 px-6">Product Information</th>
                                <th class="py-3.5 px-6">Assigned Qty</th>
                                <th class="py-3.5 px-6">Total Sold</th>
                                <th class="py-3.5 px-6">Remaining Stock</th>
                                <th class="py-3.5 px-6">Selling Price</th>
                                <th class="py-3.5 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                            @forelse($inventoryBreakdown as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            <img class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-800" src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}">
                                            <div>
                                                <div class="font-bold text-slate-900 dark:text-white">{{ $item['name'] }}</div>
                                                <div class="text-[11px] font-mono text-slate-400">SKU: {{ $item['sku'] }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $item['assigned_qty'] }} units
                                    </td>

                                    <td class="py-4 px-6 font-bold text-teal-600 dark:text-teal-400">
                                        {{ $item['sold_qty'] }} units
                                    </td>

                                    <td class="py-4 px-6">
                                        @if($item['remaining_qty'] <= 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300">
                                                0 units (Sold Out)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">
                                                {{ $item['remaining_qty'] }} units remaining
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 font-semibold text-slate-700 dark:text-slate-300">
                                        LKR {{ number_format($item['selling_price'], 2) }}
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        @if($item['remaining_qty'] > 0)
                                            <a href="{{ route('sales.create', ['product_id' => $item['product_id']]) }}" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-[11px] inline-block shadow-sm transition-all">
                                                + Record Sale
                                            </a>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">No Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                        No product allocations have been assigned to your account yet. Contact Admin for inventory allocation.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const summary = @json($summary);
            const breakdown = @json($inventoryBreakdown);

            // 1. Doughnut Chart: Sold vs Remaining
            const ctxDoughnut = document.getElementById('stockDoughnutChart').getContext('2d');
            new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: ['Total Sold', 'Remaining Stock'],
                    datasets: [{
                        data: [summary.total_sold, summary.remaining_stock],
                        backgroundColor: ['#0d9488', '#6366f1'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // 2. Bar Chart: Per Product Breakdown
            const productNames = breakdown.map(i => i.name);
            const assignedQtys = breakdown.map(i => i.assigned_qty);
            const soldQtys = breakdown.map(i => i.sold_qty);
            const remainingQtys = breakdown.map(i => i.remaining_qty);

            const ctxBar = document.getElementById('inventoryBarChart').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: productNames.length ? productNames : ['No Products'],
                    datasets: [
                        { label: 'Assigned', data: assignedQtys, backgroundColor: '#94a3b8' },
                        { label: 'Sold', data: soldQtys, backgroundColor: '#0d9488' },
                        { label: 'Remaining Stock', data: remainingQtys, backgroundColor: '#6366f1' }
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
