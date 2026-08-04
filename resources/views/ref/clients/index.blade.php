<x-ref-layout>
    <x-slot name="header">
        Assigned Clients Portfolio
    </x-slot>

    <!-- Header / Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900/90 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Assigned Client Directory</h2>
            <p class="text-xs text-slate-400 mt-0.5">Manage and view details for all clients in your sales territory</p>
        </div>

        <form method="GET" action="{{ route('ref.clients.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, business, district..." class="px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none w-full sm:w-64">
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-md shadow-emerald-600/20">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'district']))
                <a href="{{ route('ref.clients.index') }}" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs rounded-xl">Clear</a>
            @endif
        </form>
    </div>

    <!-- Clients Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/70 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">Client / Business</th>
                        <th class="px-5 py-3.5">NIC / Phone</th>
                        <th class="px-5 py-3.5">Location</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $client->profile_photo_url }}" alt="{{ $client->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-700">
                                    <div>
                                        <div class="font-bold text-white text-sm">{{ $client->business_name ?? $client->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $client->name }} &bull; {{ $client->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-200">{{ $client->phone ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400">NIC: {{ $client->nic ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-slate-200 font-medium">{{ $client->district ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $client->province ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                @if($client->status === 'approved' || $client->status === 'active')
                                    <span class="px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-semibold text-[11px] rounded-full">
                                        Approved
                                    </span>
                                @elseif($client->status === 'pending')
                                    <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-semibold text-[11px] rounded-full">
                                        Pending Approval
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-semibold text-[11px] rounded-full">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('ref.clients.show', $client->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-emerald-600 text-slate-200 hover:text-white font-semibold text-xs rounded-lg transition-colors inline-flex items-center gap-1">
                                    View Profile &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                                No clients found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $clients->links() }}
        </div>
    </div>
</x-ref-layout>
