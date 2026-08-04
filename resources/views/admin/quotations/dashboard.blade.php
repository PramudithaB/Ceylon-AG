<x-admin-layout>
    <x-slot name="header">
        Quotation Management Dashboard
    </x-slot>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white rounded-2xl p-6 md:p-8 shadow-xl border border-emerald-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="px-3 py-1 bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 font-bold text-xs rounded-full uppercase tracking-wider mb-2 inline-block">
                Quotation Module
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Corporate Quotation Overview</h2>
            <p class="text-xs md:text-sm text-slate-300 mt-1 max-w-2xl">
                Manage formal price estimations, track client proposals, issue official corporate PDFs, and monitor quotation conversion lifecycles.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.quotations.create') }}" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Quotation
            </a>
            <a href="{{ route('admin.quotations.settings.edit') }}" class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700 transition-all">
                Company & Bank Settings
            </a>
        </div>
    </div>

    <!-- 6 KPI Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total -->
        <a href="{{ route('admin.quotations.index') }}" class="bg-slate-900/90 border border-slate-800 hover:border-emerald-500/50 rounded-2xl p-4 shadow-lg transition-all group">
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total</span>
            <div class="text-2xl font-black text-white mt-2 group-hover:text-emerald-400">{{ number_format($counts['total']) }}</div>
            <p class="text-[10px] text-slate-500 mt-1">All quotations</p>
        </a>

        <!-- Draft -->
        <a href="{{ route('admin.quotations.index', ['status' => 'draft']) }}" class="bg-slate-900/90 border border-slate-800 hover:border-slate-600 rounded-2xl p-4 shadow-lg transition-all group">
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Draft</span>
            <div class="text-2xl font-black text-slate-300 mt-2">{{ number_format($counts['draft']) }}</div>
            <p class="text-[10px] text-slate-500 mt-1">Unsent proposals</p>
        </a>

        <!-- Sent -->
        <a href="{{ route('admin.quotations.index', ['status' => 'sent']) }}" class="bg-slate-900/90 border border-slate-800 hover:border-cyan-500/50 rounded-2xl p-4 shadow-lg transition-all group">
            <span class="text-[11px] font-semibold text-cyan-400 uppercase tracking-wider">Sent</span>
            <div class="text-2xl font-black text-cyan-300 mt-2">{{ number_format($counts['sent']) }}</div>
            <p class="text-[10px] text-slate-500 mt-1">Emailed to client</p>
        </a>

        <!-- Accepted -->
        <a href="{{ route('admin.quotations.index', ['status' => 'accepted']) }}" class="bg-slate-900/90 border border-slate-800 hover:border-emerald-500/50 rounded-2xl p-4 shadow-lg transition-all group">
            <span class="text-[11px] font-semibold text-emerald-400 uppercase tracking-wider">Accepted</span>
            <div class="text-2xl font-black text-emerald-400 mt-2">{{ number_format($counts['accepted']) }}</div>
            <p class="text-[10px] text-slate-500 mt-1">Confirmed deals</p>
        </a>

        <!-- Rejected -->
        <a href="{{ route('admin.quotations.index', ['status' => 'rejected']) }}" class="bg-slate-900/90 border border-slate-800 hover:border-rose-500/50 rounded-2xl p-4 shadow-lg transition-all group">
            <span class="text-[11px] font-semibold text-rose-400 uppercase tracking-wider">Rejected</span>
            <div class="text-2xl font-black text-rose-400 mt-2">{{ number_format($counts['rejected']) }}</div>
            <p class="text-[10px] text-slate-500 mt-1">Declined proposals</p>
        </a>

        <!-- Expired -->
        <a href="{{ route('admin.quotations.index', ['status' => 'expired']) }}" class="bg-slate-900/90 border border-slate-800 hover:border-amber-500/50 rounded-2xl p-4 shadow-lg transition-all group">
            <span class="text-[11px] font-semibold text-amber-400 uppercase tracking-wider">Expired</span>
            <div class="text-2xl font-black text-amber-400 mt-2">{{ number_format($counts['expired']) }}</div>
            <p class="text-[10px] text-slate-500 mt-1">Past validity date</p>
        </a>
    </div>

    <!-- Recent Quotations List -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white tracking-tight">Recent Quotation Activity</h3>
                <p class="text-xs text-slate-400 mt-0.5">Latest price estimations generated</p>
            </div>
            <a href="{{ route('admin.quotations.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">
                View All Quotations &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/70 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3">Quotation #</th>
                        <th class="px-4 py-3">Customer / Business</th>
                        <th class="px-4 py-3">Grand Total</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($recentQuotations as $q)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-3 font-bold text-emerald-400">{{ $q->quotation_number }}</td>
                            <td class="px-4 py-3 font-medium text-white">
                                {{ $q->business_name ?? $q->customer_name }}
                                <span class="block text-[11px] text-slate-400 font-normal">{{ $q->customer_name }}</span>
                            </td>
                            <td class="px-4 py-3 font-extrabold text-white">LKR {{ number_format($q->grand_total, 2) }}</td>
                            <td class="px-4 py-3">
                                @if($q->status === 'accepted')
                                    <span class="px-2.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-[10px] rounded-full">Accepted</span>
                                @elseif($q->status === 'sent')
                                    <span class="px-2.5 py-0.5 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 font-bold text-[10px] rounded-full">Sent</span>
                                @elseif($q->status === 'draft')
                                    <span class="px-2.5 py-0.5 bg-slate-800 text-slate-300 font-bold text-[10px] rounded-full">Draft</span>
                                @elseif($q->status === 'rejected')
                                    <span class="px-2.5 py-0.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold text-[10px] rounded-full">Rejected</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold text-[10px] rounded-full">Expired</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-400">{{ $q->quotation_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.quotations.show', $q->id) }}" class="px-3 py-1 bg-slate-800 hover:bg-emerald-600 text-slate-200 hover:text-white rounded-lg font-semibold text-[11px] transition-colors inline-block">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                                No quotations created yet. Click "Create New Quotation" to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
