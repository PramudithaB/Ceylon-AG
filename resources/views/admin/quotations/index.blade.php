<x-admin-layout>
    <x-slot name="header">
        Quotation Directory
    </x-slot>

    <!-- Top Action & Filter Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-slate-900/90 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <div>
            <h2 class="text-xl font-extrabold text-white tracking-tight">All Corporate Quotations</h2>
            <p class="text-xs text-slate-400 mt-0.5">Filter, search, print, download, and email official company quotations</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.quotations.create') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Quotation
            </a>
            <a href="{{ route('admin.quotations.settings.edit') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700">
                Settings
            </a>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-slate-800">
        <a href="{{ route('admin.quotations.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request('status') ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' }}">
            All ({{ $counts['total'] }})
        </a>
        <a href="{{ route('admin.quotations.index', ['status' => 'draft']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'draft' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' }}">
            Draft ({{ $counts['draft'] }})
        </a>
        <a href="{{ route('admin.quotations.index', ['status' => 'sent']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'sent' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' }}">
            Sent ({{ $counts['sent'] }})
        </a>
        <a href="{{ route('admin.quotations.index', ['status' => 'accepted']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'accepted' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' }}">
            Accepted ({{ $counts['accepted'] }})
        </a>
        <a href="{{ route('admin.quotations.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'rejected' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' }}">
            Rejected ({{ $counts['rejected'] }})
        </a>
        <a href="{{ route('admin.quotations.index', ['status' => 'expired']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'expired' ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' }}">
            Expired ({{ $counts['expired'] }})
        </a>
    </div>

    <!-- Search & Filter Form -->
    <div class="bg-slate-900/90 border border-slate-800 p-4 rounded-2xl shadow-xl">
        <form method="GET" action="{{ route('admin.quotations.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Quotation #, customer name, business, or email..." class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none">
            </div>

            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none" title="From Date">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                    Filter Results
                </button>
                @if(request()->anyFilled(['search', 'date_from', 'date_to']))
                    <a href="{{ route('admin.quotations.index', request()->only('status')) }}" class="px-3 py-2 bg-slate-800 text-slate-400 hover:text-white text-xs font-semibold rounded-xl">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Quotations Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/80 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">Quotation #</th>
                        <th class="px-5 py-3.5">Customer & Business</th>
                        <th class="px-5 py-3.5">Quotation Date</th>
                        <th class="px-5 py-3.5">Expiry Date</th>
                        <th class="px-5 py-3.5">Grand Total</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($quotations as $q)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4 font-extrabold text-emerald-400">
                                <a href="{{ route('admin.quotations.show', $q->id) }}" class="hover:underline">
                                    {{ $q->quotation_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-white text-sm">{{ $q->business_name ?? $q->customer_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $q->customer_name }} &bull; {{ $q->email ?? 'No email' }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-300">{{ $q->quotation_date->format('M d, Y') }}</td>
                            <td class="px-5 py-4 text-slate-300">
                                {{ $q->expiry_date->format('M d, Y') }}
                                @if($q->expiry_date->isPast() && $q->status !== 'accepted')
                                    <span class="text-[10px] font-bold text-rose-400 block">Expired</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-black text-white text-sm">LKR {{ number_format($q->grand_total, 2) }}</td>
                            <td class="px-5 py-4">
                                @if($q->status === 'accepted')
                                    <span class="px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold text-[10px] rounded-full">Accepted</span>
                                @elseif($q->status === 'sent')
                                    <span class="px-2.5 py-1 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 font-bold text-[10px] rounded-full">Sent</span>
                                @elseif($q->status === 'draft')
                                    <span class="px-2.5 py-1 bg-slate-800 text-slate-300 font-bold text-[10px] rounded-full">Draft</span>
                                @elseif($q->status === 'rejected')
                                    <span class="px-2.5 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold text-[10px] rounded-full">Rejected</span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold text-[10px] rounded-full">Expired</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.quotations.show', $q->id) }}" class="px-2.5 py-1 bg-slate-800 hover:bg-emerald-600 text-slate-200 hover:text-white rounded-lg text-[11px] font-semibold transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('admin.quotations.print', $q->id) }}" target="_blank" class="px-2.5 py-1 bg-slate-800 hover:bg-teal-600 text-slate-200 hover:text-white rounded-lg text-[11px] font-semibold transition-colors">
                                        Print
                                    </a>
                                    <a href="{{ route('admin.quotations.pdf', $q->id) }}" class="px-2.5 py-1 bg-slate-800 hover:bg-cyan-600 text-slate-200 hover:text-white rounded-lg text-[11px] font-semibold transition-colors">
                                        PDF
                                    </a>
                                    <form method="POST" action="{{ route('admin.quotations.duplicate', $q->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 bg-slate-800 hover:bg-amber-600 text-slate-200 hover:text-white rounded-lg text-[11px] font-semibold transition-colors" title="Duplicate as new Draft">
                                            Copy
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.quotations.destroy', $q->id) }}" onsubmit="return confirm('Are you sure you want to delete this quotation?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white rounded-lg text-[11px] font-semibold transition-colors">
                                            Del
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                                No quotations found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $quotations->links() }}
        </div>
    </div>
</x-admin-layout>
