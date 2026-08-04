<x-ref-layout>
    <x-slot name="header">
        Sales Representative Dashboard
    </x-slot>

    <!-- Welcome Header Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 border border-emerald-500/20 p-6 md:p-8 shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full mb-3 inline-block">
                    Representative Portal
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                    Welcome back, {{ $refUser->name }}!
                </h2>
                <p class="text-sm text-slate-300 mt-1 max-w-2xl">
                    Here is your sales performance overview, client portfolio status, and financial metrics.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('ref.stock-requests.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Stock Request
                </a>
            </div>
        </div>
    </div>

    <!-- 5 KPI Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- 1. Assigned Clients -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-emerald-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Assigned Clients</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-extrabold text-white tracking-tight">{{ number_format($assignedClientsCount) }}</div>
                <p class="text-[11px] text-slate-400 mt-1">Active client portfolio</p>
            </div>
        </div>

        <!-- 2. Assigned Products -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-teal-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Assigned Products</span>
                <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-extrabold text-white tracking-tight">{{ number_format($assignedProductsCount) }}</div>
                <p class="text-[11px] text-slate-400 mt-1">Available catalog items</p>
            </div>
        </div>

        <!-- 3. Monthly Sales -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-cyan-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Monthly Sales</span>
                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-extrabold text-white tracking-tight">LKR {{ number_format($currentMonthSales, 2) }}</div>
                <p class="text-[11px] text-slate-400 mt-1">Total revenue this month</p>
            </div>
        </div>

        <!-- 4. Pending Payments -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-amber-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Payments</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-extrabold text-white tracking-tight">{{ number_format($pendingPaymentsCount) }}</div>
                <p class="text-[11px] text-slate-400 mt-1">Awaiting admin review</p>
            </div>
        </div>

        <!-- 5. Total Commission (placeholder) -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-emerald-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Commission</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-extrabold text-emerald-400 tracking-tight">LKR {{ number_format($totalCommission, 2) }}</div>
                <p class="text-[11px] text-slate-400 mt-1">Estimated earnings (5%)</p>
            </div>
        </div>
    </div>

    <!-- Interactive Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Sales Chart -->
        <div class="lg:col-span-2 bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white">Monthly Sales Trend</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Sales performance over the last 6 months</p>
                </div>
                <span class="text-xs font-semibold text-emerald-400 bg-emerald-950/60 border border-emerald-500/30 px-2.5 py-1 rounded-lg">
                    Revenue Trend
                </span>
            </div>
            <div class="relative h-72">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Client Performance Chart -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-white">Client Performance</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Top clients by sales contribution</p>
                </div>
            </div>
            <div class="relative h-72 flex items-center justify-center">
                <canvas id="clientPerformanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Client Sales Activity -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-white">Recent Sales Activity</h3>
                <p class="text-xs text-slate-400 mt-0.5">Latest transactions recorded for assigned clients</p>
            </div>
            <a href="{{ route('ref.sales.index') }}" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300">
                View All Sales &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3">Sale #</th>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Total Amount</th>
                        <th class="px-4 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($recentSales as $sale)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-3 font-semibold text-emerald-400">{{ $sale->sale_number }}</td>
                            <td class="px-4 py-3 font-medium text-white">{{ $sale->client->business_name ?? $sale->client->name }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $sale->product->name ?? 'Product' }}</td>
                            <td class="px-4 py-3 font-semibold text-white">{{ $sale->quantity }}</td>
                            <td class="px-4 py-3 font-bold text-white">LKR {{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $sale->sold_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                No sales activity recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Monthly Sales Line Chart
            const salesCtx = document.getElementById('monthlySalesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlySalesChart['labels']) !!},
                    datasets: [{
                        label: 'Sales Revenue (LKR)',
                        data: {!! json_encode($monthlySalesChart['data']) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.15)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                        },
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                        }
                    }
                }
            });

            // Chart 2: Client Performance Doughnut Chart
            const perfCtx = document.getElementById('clientPerformanceChart').getContext('2d');
            new Chart(perfCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($clientPerformanceChart['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($clientPerformanceChart['data']) !!},
                        backgroundColor: ['#10b981', '#14b8a6', '#06b6d4', '#3b82f6', '#8b5cf6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#cbd5e1', font: { family: 'Plus Jakarta Sans', size: 11 }, padding: 12 }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
    @endpush
</x-ref-layout>
