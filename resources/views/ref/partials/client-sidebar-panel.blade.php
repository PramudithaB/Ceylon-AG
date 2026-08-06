<!-- Left Sidebar Panel: My Clients Directory -->
<div class="w-full lg:w-96 shrink-0 bg-white/90 backdrop-blur-xl border border-gray-100/80 rounded-3xl p-5 shadow-xl shadow-emerald-950/5 flex flex-col h-[calc(100vh-140px)] sticky top-24">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] flex items-center justify-center text-white shadow-md shadow-emerald-700/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h2 class="text-base font-extrabold text-gray-900 tracking-tight">My Assigned Clients</h2>
                <p class="text-[11px] text-gray-400 font-semibold">{{ $assignedClients->count() }} Portfolio Accounts</p>
            </div>
        </div>

        <span class="px-2.5 py-1 bg-emerald-50 text-[#1E8E3E] border border-emerald-200 text-[10px] font-extrabold rounded-full">
            Live CRM
        </span>
    </div>

    <!-- Search & Filter Controls -->
    <form method="GET" action="{{ route('ref.dashboard') }}" class="py-4 border-b border-gray-100 space-y-2.5">
        <div class="relative">
            <input type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Search name, business, district..." 
                class="auth-input pl-9 pr-3 py-2 bg-gray-50/80 border border-gray-200/80 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white w-full"
            />
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <select name="status" onchange="this.form.submit()" class="auth-input px-3 py-1.5 bg-gray-50/80 border border-gray-200/80 rounded-xl text-[11px] font-medium text-gray-700 focus:bg-white">
                <option value="">All Statuses</option>
                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>

            <select name="district" onchange="this.form.submit()" class="auth-input px-3 py-1.5 bg-gray-50/80 border border-gray-200/80 rounded-xl text-[11px] font-medium text-gray-700 focus:bg-white">
                <option value="">All Districts</option>
                @foreach($assignedClients->pluck('district')->unique()->filter() as $dist)
                    <option value="{{ $dist }}" {{ $district == $dist ? 'selected' : '' }}>{{ $dist }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Scrollable Client List -->
    <div class="flex-1 overflow-y-auto space-y-2 pr-1 pt-2 custom-scrollbar">
        @forelse($assignedClients as $clientItem)
            @php
                $isSelected = $selectedClient && $selectedClient->id === $clientItem->id;
            @endphp
            <div 
                onclick="switchClientWorkspace({{ $clientItem->id }})"
                id="client-card-{{ $clientItem->id }}"
                class="client-item-card cursor-pointer p-3.5 rounded-2xl transition-all duration-200 border {{ $isSelected ? 'bg-gradient-to-r from-emerald-500/10 to-emerald-500/5 border-[#1E8E3E] shadow-md shadow-emerald-700/10 ring-1 ring-[#1E8E3E]' : 'bg-white hover:bg-gray-50/80 border-gray-100' }}"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $clientItem->profile_photo_url }}" alt="{{ $clientItem->name }}" class="w-10 h-10 rounded-2xl object-cover border-2 border-emerald-100 shrink-0">
                        <div>
                            <h3 class="text-xs font-extrabold text-gray-900 line-clamp-1">{{ $clientItem->business_name ?? $clientItem->name }}</h3>
                            <p class="text-[11px] text-gray-500 font-medium line-clamp-1">{{ $clientItem->name }} &bull; {{ $clientItem->district ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($clientItem->status === 'approved' || $clientItem->status === 'active')
                        <span class="px-2 py-0.5 bg-emerald-100 text-[#1E8E3E] text-[9px] font-extrabold rounded-full shrink-0">
                            Active
                        </span>
                    @else
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[9px] font-extrabold rounded-full shrink-0">
                            {{ ucfirst($clientItem->status) }}
                        </span>
                    @endif
                </div>

                <div class="mt-2.5 pt-2 border-t border-gray-100/80 flex items-center justify-between text-[10px] font-semibold text-gray-500">
                    <div>
                        <span class="text-gray-400">Due:</span> 
                        <span class="font-extrabold {{ $clientItem->outstanding_balance > 0 ? 'text-rose-600' : 'text-[#1E8E3E]' }}">
                            LKR {{ number_format($clientItem->outstanding_balance, 2) }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-400">Paid:</span> {{ $clientItem->last_payment_date }}
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-xs text-gray-400 font-medium bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                No assigned clients found matching your query.
            </div>
        @endforelse
    </div>
</div>
