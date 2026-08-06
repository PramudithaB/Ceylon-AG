<x-ref-layout>
    <x-slot name="header">
        Assigned Clients Portfolio
    </x-slot>

    <!-- Filter & Search Bar Card -->
    <div class="ref-card rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Assigned Client Directory</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Manage and view details for all clients in your sales territory</p>
        </div>

        <form method="GET" action="{{ route('ref.clients.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search name, business, district..." 
                    class="auth-input pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white w-full sm:w-64"
                >
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-md hover:opacity-95 transition-all">
                Search
            </button>
            
            @if(request()->anyFilled(['search', 'district']))
                <a href="{{ route('ref.clients.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-2xl transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Clients Table Card -->
    <div class="ref-card rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-medium text-gray-700">
                <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Client / Business Name</th>
                        <th class="px-6 py-4">Contact Info</th>
                        <th class="px-6 py-4">Territory Location</th>
                        <th class="px-6 py-4">Account Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clients as $client)
                        <tr class="hover:bg-emerald-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $client->profile_photo_url }}" alt="{{ $client->name }}" class="w-10 h-10 rounded-2xl object-cover border-2 border-emerald-100">
                                    <div>
                                        <div class="font-extrabold text-gray-900 text-sm">{{ $client->business_name ?? $client->name }}</div>
                                        <div class="text-[11px] text-gray-500 font-medium">{{ $client->name }} &bull; {{ $client->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $client->phone ?? 'N/A' }}</div>
                                <div class="text-[11px] text-gray-500 font-medium">NIC: {{ $client->nic ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $client->district ?? 'N/A' }}</div>
                                <div class="text-[11px] text-gray-500 font-medium">{{ $client->province ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($client->status === 'approved' || $client->status === 'active')
                                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-[#1E8E3E] font-extrabold text-[11px] rounded-full">
                                        Approved Active
                                    </span>
                                @elseif($client->status === 'pending')
                                    <span class="px-3 py-1 bg-amber-50 border border-amber-200 text-amber-700 font-extrabold text-[11px] rounded-full">
                                        Pending Review
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-rose-50 border border-rose-200 text-rose-600 font-extrabold text-[11px] rounded-full">
                                        {{ ucfirst($client->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('ref.clients.show', $client->id) }}" class="px-4 py-2 bg-emerald-50 hover:bg-[#1E8E3E] text-[#1E8E3E] hover:text-white font-extrabold text-xs rounded-xl transition-all inline-flex items-center gap-1">
                                    <span>View Profile</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">
                                No clients found matching your search query.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $clients->links() }}
        </div>
    </div>
</x-ref-layout>
