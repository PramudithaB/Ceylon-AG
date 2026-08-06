@extends('layouts.ref')

@section('title', 'Ref Client Workspace Dashboard - Ceylon AG')

@section('content')
<div class="space-y-6">
    
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-[#1E8E3E] font-bold text-xs flex items-center justify-between shadow-sm animate-fadeIn">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-black">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs space-y-1 shadow-sm">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- TOP CLIENT SELECTOR BANNER (SINGLE DASHBOARD INTEGRATED) -->
    <div class="ref-card rounded-3xl p-6 sm:p-8 bg-gradient-to-r from-white via-emerald-50/40 to-white border border-emerald-100 shadow-xl shadow-emerald-950/5 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-emerald-100 text-[#1E8E3E] border border-emerald-200 text-[10px] font-extrabold uppercase rounded-full tracking-wider">
                        Client Workspace Dashboard
                    </span>
                    <span class="text-xs font-semibold text-gray-400">&bull; {{ $assignedClients->count() }} Portfolio Accounts</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Select Client Workspace</h1>
                <p class="text-xs text-gray-500 font-medium">Choose an assigned client below to manage financial balances, stock, orders, payments, and notes instantly inside this dashboard.</p>
            </div>

            <!-- SEARCHABLE CLIENT SELECTOR DROPDOWN / EMPTY STATE -->
            @if(($allClients ?? $assignedClients)->isEmpty())
                <div class="w-full md:w-96 shrink-0 p-4 bg-amber-50/80 border border-amber-200 rounded-2xl text-amber-800 text-xs font-bold flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>You currently have no assigned clients.</span>
                </div>
            @else
                <div class="w-full md:w-96 shrink-0 relative" x-data="{ search: '' }">
                    <label class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                        <span>Select Client *</span>
                        <span class="text-[10px] text-gray-400 font-semibold lowercase">search by business, owner, phone</span>
                    </label>

                    <div class="relative">
                        <select 
                            id="top-client-select"
                            onchange="selectClientFromDropdown(this.value)" 
                            class="auth-input block w-full px-4 py-3.5 bg-white border-2 border-emerald-600/30 rounded-2xl text-xs font-extrabold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-md cursor-pointer appearance-none pr-10"
                        >
                            <option value="" {{ !$selectedClient ? 'selected' : '' }}>-- Select Client --</option>
                            @foreach(($allClients ?? $assignedClients) as $clientItem)
                                <option value="{{ $clientItem->id }}" {{ $selectedClient && $selectedClient->id === $clientItem->id ? 'selected' : '' }}>
                                    {{ $clientItem->business_name ?? $clientItem->name }} &bull; Owner: {{ $clientItem->name }} &bull; Phone: {{ $clientItem->phone ?? 'N/A' }} ({{ $clientItem->district ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#1E8E3E]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- DYNAMIC CLIENT WORKSPACE CONTAINER (LOADS IN SAME DASHBOARD WITHOUT REDIRECT) -->
    <div id="workspace-container" class="transition-all duration-300">
        @if($assignedClients->isEmpty())
            <div class="ref-card rounded-3xl p-16 text-center flex flex-col items-center justify-center min-h-[420px] bg-white border border-gray-100 shadow-xl shadow-emerald-950/5">
                <div class="w-20 h-20 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mb-5 border-2 border-amber-100 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">You currently have no assigned clients.</h2>
                <p class="text-xs text-gray-500 font-medium max-w-md mt-2 leading-relaxed">
                    No client accounts are currently assigned to your sales profile. Please contact an Administrator to assign client accounts to your profile.
                </p>
            </div>
        @elseif(!$selectedClient)
            <div class="ref-card rounded-3xl p-16 text-center flex flex-col items-center justify-center min-h-[420px] bg-white border border-gray-100 shadow-xl shadow-emerald-950/5">
                <div class="w-20 h-20 rounded-full bg-emerald-50 text-[#1E8E3E] flex items-center justify-center mb-5 border-2 border-emerald-100 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Please select a client.</h2>
                <p class="text-xs text-gray-500 font-medium max-w-md mt-2 leading-relaxed">
                    Choose a client from the <strong class="text-gray-900">Select Client</strong> dropdown at the top of the dashboard. All financial metrics, product inventory, order history, payment history, and action forms will load right here inside this dashboard instantly.
                </p>

                <!-- Quick Select Pills -->
                <div class="mt-8 pt-6 border-t border-gray-100 w-full max-w-lg">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-3">Or quick select an assigned client:</span>
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        @foreach($assignedClients->take(6) as $clientQuick)
                            <button 
                                onclick="selectClientFromDropdown({{ $clientQuick->id }})" 
                                class="px-3.5 py-2 bg-gray-50 hover:bg-emerald-50 text-gray-700 hover:text-[#1E8E3E] font-bold text-xs rounded-2xl border border-gray-200 hover:border-emerald-200 transition-all flex items-center gap-2">
                                <img src="{{ $clientQuick->profile_photo_url }}" alt="{{ $clientQuick->name }}" class="w-5 h-5 rounded-full object-cover">
                                <span>{{ $clientQuick->business_name ?? $clientQuick->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            @include('ref.partials.client-workspace-panel')
        @endif
    </div>

</div>

<!-- Real-Time Workspace Switching Script (No Redirect / No Page Reload) -->
<script>
    function selectClientFromDropdown(clientId) {
        if (!clientId) return;

        // Update select dropdown element if called programmatically
        const selectElem = document.getElementById('top-client-select');
        if (selectElem && selectElem.value != clientId) {
            selectElem.value = clientId;
        }

        const container = document.getElementById('workspace-container');
        if (!container) return;

        // Show subtle opacity loading state
        container.style.opacity = '0.4';

        fetch(`/ref/workspace/client/${clientId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.html) {
                container.innerHTML = data.html;
                container.style.opacity = '1';
                
                // Update URL parameter cleanly without reloading page or redirecting
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('client_id', clientId);
                window.history.pushState({ clientId: clientId }, '', newUrl);

                // Scroll smoothly to client workspace top
                container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        })
        .catch(err => {
            console.error('Error loading client workspace:', err);
            container.style.opacity = '1';
        });
    }
</script>
@endsection
