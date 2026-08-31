@extends('layouts.ref')

@section('title', 'Ref Dashboard - Ceylon AG')

@section('header', 'Ref Dashboard')

@section('content')
<div class="space-y-5">

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-[#1E8E3E] font-bold text-xs flex items-center justify-between shadow-xs animate-fadeIn">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-black">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs space-y-1 shadow-xs">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- 1. WELCOME & GREETING HEADER -->
    <div class="ref-card rounded-3xl p-5 sm:p-6 bg-gradient-to-r from-white via-emerald-50/40 to-white border border-emerald-100 shadow-xs flex items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 bg-emerald-100 text-[#1E8E3E] font-extrabold text-[10px] uppercase rounded-full tracking-wider">
                    Sales Representative
                </span>
            </div>
            <h1 class="text-lg sm:text-2xl font-extrabold text-gray-900 tracking-tight">Welcome, {{ $refUser->full_name }}</h1>
            <p class="text-xs text-gray-500 font-medium">Select a client below to perform stock requests, sales, and payments.</p>
        </div>
        
        <div class="hidden sm:flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500 bg-white px-3 py-1.5 rounded-xl border border-emerald-100 shadow-xs">
                {{ $clients->count() }} Portfolio Clients
            </span>
        </div>
    </div>

    <!-- 2. ACTIVE CLIENT CARD & SEARCHABLE SELECTOR -->
    <div class="ref-card rounded-3xl p-5 bg-white border border-gray-100 shadow-xs space-y-4">
        
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">
                Active Client Context
            </span>
            @if($selectedClient)
                <button type="button" onclick="openClientModal()" class="text-xs font-extrabold text-[#1E8E3E] hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Change Client</span>
                </button>
            @endif
        </div>

        @if($clients->isEmpty())
            <!-- NO CLIENTS IN SYSTEM EMPTY STATE -->
            <div class="p-6 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-800 text-xs font-bold flex items-center gap-3">
                <svg class="w-6 h-6 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="font-extrabold text-sm text-amber-900">You currently have no assigned clients.</p>
                    <p class="text-xs text-amber-700 font-medium mt-0.5">Please contact an Administrator to assign client accounts to your territory sales profile.</p>
                </div>
            </div>
        @elseif($selectedClient)
            <!-- ACTIVE SELECTED CLIENT BANNER -->
            <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-emerald-500/10 via-emerald-50/50 to-white border-2 border-emerald-500/30 relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] text-white flex items-center justify-center font-black text-lg shadow-md shrink-0">
                            {{ substr($selectedClient->business_name ?? $selectedClient->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 bg-emerald-600 text-white font-extrabold text-[9px] uppercase rounded-full">
                                    Active Client
                                </span>
                                <span class="text-xs font-bold text-gray-500">{{ $selectedClient->district ?? 'Sri Lanka' }}</span>
                            </div>
                            <h2 class="text-base sm:text-lg font-black text-gray-900 mt-0.5">{{ $selectedClient->business_name ?? $selectedClient->name }}</h2>
                            <p class="text-xs text-gray-600 font-medium">
                                Contact: <span class="font-bold text-gray-900">{{ $selectedClient->name }}</span> &bull; 
                                Phone: <a href="tel:{{ $selectedClient->phone }}" class="font-bold text-[#1E8E3E] underline">{{ $selectedClient->phone ?? 'N/A' }}</a>
                            </p>
                        </div>
                    </div>

                    <!-- Action buttons on Selected Client -->
                    <div class="flex items-center gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-emerald-100">
                        <button type="button" onclick="openClientModal()" class="touch-btn flex-1 sm:flex-none px-4 py-2.5 bg-white hover:bg-emerald-50 text-gray-800 hover:text-[#1E8E3E] font-extrabold text-xs rounded-xl border border-emerald-200 shadow-xs transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4 text-[#1E8E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span>Change Client</span>
                        </button>
                    </div>
                </div>

                <!-- Financial Mini-Bar for Active Client -->
                @if($clientSummary)
                    <div class="mt-4 pt-3 border-t border-emerald-200/60 grid grid-cols-2 sm:grid-cols-3 gap-2 text-center">
                        <div class="p-2 rounded-xl bg-white/80 border border-emerald-100">
                            <span class="text-[10px] font-bold text-gray-400 uppercase block">Outstanding Due</span>
                            <span class="text-xs sm:text-sm font-extrabold text-rose-600">LKR {{ number_format($clientSummary['outstanding_balance'], 2) }}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-white/80 border border-emerald-100">
                            <span class="text-[10px] font-bold text-gray-400 uppercase block">Total Paid</span>
                            <span class="text-xs sm:text-sm font-extrabold text-[#1E8E3E]">LKR {{ number_format($clientSummary['total_paid'], 2) }}</span>
                        </div>
                        <div class="col-span-2 sm:col-span-1 p-2 rounded-xl bg-white/80 border border-emerald-100">
                            <span class="text-[10px] font-bold text-gray-400 uppercase block">Assigned Items</span>
                            <span class="text-xs sm:text-sm font-extrabold text-gray-900">{{ $clientAssignedProducts->count() }} In Inventory</span>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- NO CLIENT SELECTED PROMPT -->
            <div class="p-6 rounded-2xl bg-amber-50/70 border border-amber-200 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-700 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-gray-900">Please select a client.</h3>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Select a client from your portfolio to enable quick stock requests, sales, and payments.</p>
                </div>
                <div>
                    <button type="button" onclick="openClientModal()" class="touch-btn px-6 py-3 bg-[#1E8E3E] hover:bg-emerald-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 mx-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Select Client</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- 3. MOBILE-FIRST QUICK ACTIONS GRID (TOUCH-FRIENDLY) -->
    <div>
        <div class="flex items-center justify-between mb-2.5 px-1">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-500">Quick Actions</h2>
            @if($selectedClient)
                <span class="text-[10px] font-bold text-[#1E8E3E]">Active: {{ $selectedClient->business_name ?? $selectedClient->name }}</span>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            
            <!-- 1. STOCK REQUEST -->
            <button 
                type="button" 
                onclick="{{ $selectedClient ? 'openStockModal()' : 'openClientModal()' }}" 
                class="ref-card rounded-2xl p-4 text-left transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer group flex flex-col justify-between min-h-[105px]"
            >
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#1E8E3E] group-hover:bg-[#1E8E3E] group-hover:text-white flex items-center justify-center transition-colors shadow-xs mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-black text-gray-900 block group-hover:text-[#1E8E3E] transition-colors">Create Stock Request</span>
                    <span class="text-[10px] text-gray-400 font-semibold">Request for client</span>
                </div>
            </button>

            <!-- 2. SALES -->
            <button 
                type="button" 
                onclick="{{ $selectedClient ? 'openSalesModal()' : 'openClientModal()' }}" 
                class="ref-card rounded-2xl p-4 text-left transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer group flex flex-col justify-between min-h-[105px]"
            >
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-colors shadow-xs mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-black text-gray-900 block group-hover:text-blue-600 transition-colors">Record Sale</span>
                    <span class="text-[10px] text-gray-400 font-semibold">Sell client stock</span>
                </div>
            </button>

            <!-- 3. PAYMENTS -->
            <button 
                type="button" 
                onclick="{{ $selectedClient ? 'openPaymentModal()' : 'openClientModal()' }}" 
                class="ref-card rounded-2xl p-4 text-left transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer group flex flex-col justify-between min-h-[105px]"
            >
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center transition-colors shadow-xs mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-black text-gray-900 block group-hover:text-amber-600 transition-colors">Collect Payment</span>
                    <span class="text-[10px] text-gray-400 font-semibold">Submit payment</span>
                </div>
            </button>

            <!-- 4. REPORTS -->
            <a 
                href="{{ route('ref.sales.reports') }}" 
                class="ref-card rounded-2xl p-4 text-left transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer group flex flex-col justify-between min-h-[105px]"
            >
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white flex items-center justify-center transition-colors shadow-xs mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-black text-gray-900 block group-hover:text-purple-600 transition-colors">Reports</span>
                    <span class="text-[10px] text-gray-400 font-semibold">Analytics & summaries</span>
                </div>
            </a>

        </div>
    </div>

    <!-- STATUS SUMMARY QUICK BAR -->
    <div class="grid grid-cols-3 gap-2.5">
        <div class="ref-card rounded-2xl p-3 text-center bg-white border border-amber-100">
            <span class="text-[10px] font-bold text-amber-700 uppercase block">Pending</span>
            <span class="text-sm font-extrabold text-amber-800">{{ $statusCounts['pending_requests'] + $statusCounts['pending_payments'] }}</span>
        </div>
        <div class="ref-card rounded-2xl p-3 text-center bg-white border border-emerald-100">
            <span class="text-[10px] font-bold text-emerald-700 uppercase block">Approved</span>
            <span class="text-sm font-extrabold text-[#1E8E3E]">{{ $statusCounts['approved_requests'] }}</span>
        </div>
        <div class="ref-card rounded-2xl p-3 text-center bg-white border border-rose-100">
            <span class="text-[10px] font-bold text-rose-700 uppercase block">Rejected</span>
            <span class="text-sm font-extrabold text-rose-800">0</span>
        </div>
    </div>

    <!-- 4. RECENT ACTIVITY & RECENT STOCK REQUESTS STREAM (MOBILE CARDS) -->
    <div class="ref-card rounded-3xl p-5 bg-white border border-gray-100 shadow-xs space-y-3">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h2 class="text-sm font-black text-gray-900 tracking-tight">Recent Activity &amp; Recent Stock Requests</h2>
                <p class="text-[11px] text-gray-400 font-medium">Recent transactions for your clients</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ref.stock-requests.index') }}" class="text-[11px] font-bold text-[#1E8E3E] hover:underline">Stock Requests</a>
                <span class="text-gray-300">&bull;</span>
                <a href="{{ route('ref.payments.index') }}" class="text-[11px] font-bold text-[#1E8E3E] hover:underline">Payments</a>
            </div>
        </div>

        @if($recentActivity->isEmpty())
            <div class="py-8 text-center text-xs text-gray-400 font-medium">
                No recent activity recorded yet.
            </div>
        @else
            <div class="space-y-2.5">
                @foreach($recentActivity as $activity)
                    <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-100 flex items-center justify-between gap-3 hover:bg-gray-100/60 transition-colors">
                        
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Icon -->
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 
                                @if($activity['type'] === 'stock_request') bg-emerald-100 text-[#1E8E3E]
                                @elseif($activity['type'] === 'sale') bg-blue-100 text-blue-600
                                @else bg-amber-100 text-amber-600 @endif">
                                @if($activity['type'] === 'stock_request')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                @elseif($activity['type'] === 'sale')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-black text-gray-900 truncate">{{ $activity['client_name'] }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 font-medium truncate">
                                    {{ $activity['product_name'] }} &bull; <span class="font-bold text-gray-700">{{ $activity['detail'] }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Status & Date -->
                        <div class="text-right shrink-0">
                            @if($activity['status'] === 'approved' || $activity['status'] === 'completed')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-100 text-[#1E8E3E]">
                                    {{ ucfirst($activity['status']) }}
                                </span>
                            @elseif($activity['status'] === 'rejected')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-rose-100 text-rose-700">
                                    Rejected
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-100 text-amber-800">
                                    Pending
                                </span>
                            @endif
                            <span class="text-[10px] text-gray-400 font-semibold block mt-1">{{ $activity['date_formatted'] }}</span>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<!-- ==================================================== -->
<!-- 1. SEARCHABLE CLIENT SELECTION MODAL (MOBILE-FIRST) -->
<!-- ==================================================== -->
<div id="client-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl max-h-[85vh] flex flex-col shadow-2xl animate-slideUp">
        
        <!-- Modal Header -->
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-gray-900">Select Client</h3>
                <p class="text-xs text-gray-400 font-medium">Pick a client to set as active for stock, sales & payments</p>
            </div>
            <button type="button" onclick="closeClientModal()" class="p-2 text-gray-400 hover:text-gray-700 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Search Input -->
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <div class="relative">
                <input 
                    type="text" 
                    id="client-search-input" 
                    oninput="filterClients(this.value)"
                    placeholder="Search by business name, owner, city or phone..." 
                    class="w-full px-4 py-3 pl-10 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs"
                >
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Clients List (Touch Targets) -->
        <div id="clients-list-container" class="flex-1 overflow-y-auto p-4 space-y-2 max-h-[50vh] custom-scrollbar">
            @forelse($clients as $c)
                <button 
                    type="button" 
                    onclick="selectClientAndRedirect({{ $c->id }})" 
                    class="client-list-item w-full p-3.5 rounded-2xl border text-left transition-all hover:border-emerald-300 hover:bg-emerald-50/50 flex items-center justify-between gap-3 {{ $selectedClient && $selectedClient->id === $c->id ? 'border-[#1E8E3E] bg-emerald-50/80 shadow-xs' : 'border-gray-100 bg-white' }}"
                    data-search="{{ strtolower($c->business_name . ' ' . $c->name . ' ' . $c->phone . ' ' . $c->district) }}"
                >
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-gray-900 truncate">{{ $c->business_name ?? $c->name }}</span>
                            @if($selectedClient && $selectedClient->id === $c->id)
                                <span class="px-2 py-0.5 bg-[#1E8E3E] text-white text-[9px] font-black rounded-full uppercase">Active</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-500 font-medium mt-0.5 truncate">
                            Owner: {{ $c->name }} &bull; Phone: {{ $c->phone ?? 'N/A' }} &bull; {{ $c->district ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="shrink-0 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </button>
            @empty
                <div class="py-8 text-center text-xs text-gray-400 font-medium">
                    No clients found in your portfolio.
                </div>
            @endforelse
        </div>

    </div>
</div>

<!-- ==================================================== -->
<!-- 2. QUICK STOCK REQUEST MODAL (PRE-FILLED ACTIVE CLIENT) -->
<!-- ==================================================== -->
<div id="stock-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl max-h-[90vh] flex flex-col shadow-2xl animate-slideUp">
        
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-gray-900">Request Stock for Client</h3>
                    <p class="text-[11px] text-gray-400 font-medium">Recipient: <strong class="text-gray-800">{{ $selectedClient->business_name ?? $selectedClient->name ?? 'Client' }}</strong></p>
                </div>
            </div>
            <button type="button" onclick="closeStockModal()" class="p-2 text-gray-400 hover:text-gray-700 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('ref.stock-requests.store') }}" method="POST" class="p-5 space-y-4 overflow-y-auto max-h-[75vh]">
            @csrf
            <input type="hidden" name="source" value="dashboard">
            <input type="hidden" name="client_id" value="{{ $selectedClient->id ?? '' }}" required>

            <!-- Product Select -->
            <div>
                <label for="stock_product_id" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Product *</label>
                <select id="stock_product_id" name="product_id" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                    <option value="">-- Choose Product --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->name }} (Warehouse Stock: {{ $p->stock_quantity }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantity -->
            <div>
                <label for="stock_qty" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Quantity (Units) *</label>
                <input type="number" id="stock_qty" name="requested_quantity" min="1" value="10" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
            </div>

            <!-- Notes -->
            <div>
                <label for="stock_notes" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Reason / Notes <span class="text-gray-400 font-normal lowercase">(optional)</span></label>
                <input type="text" id="stock_notes" name="notes" placeholder="e.g. Seasonal replenishment" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
            </div>

            <button type="submit" class="touch-btn w-full py-3.5 bg-[#1E8E3E] hover:bg-emerald-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Submit Stock Request</span>
            </button>
        </form>

    </div>
</div>

<!-- ==================================================== -->
<!-- 3. QUICK SALE MODAL (PRE-FILLED ACTIVE CLIENT) -->
<!-- ==================================================== -->
<div id="sales-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl max-h-[90vh] flex flex-col shadow-2xl animate-slideUp">
        
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-gray-900">Record Sale for Client</h3>
                    <p class="text-[11px] text-gray-400 font-medium">Selling from: <strong class="text-gray-800">{{ $selectedClient->business_name ?? $selectedClient->name ?? 'Client' }}</strong></p>
                </div>
            </div>
            <button type="button" onclick="closeSalesModal()" class="p-2 text-gray-400 hover:text-gray-700 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('ref.sales.store') }}" method="POST" class="p-5 space-y-4 overflow-y-auto max-h-[75vh]">
            @csrf
            <input type="hidden" name="source" value="dashboard">
            <input type="hidden" name="client_id" value="{{ $selectedClient->id ?? '' }}" required>

            <!-- Product from Client's Assigned Inventory -->
            <div>
                <label for="sale_product_id" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Product in Client Stock *</label>
                @if($clientAssignedProducts->isEmpty())
                    <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold">
                        This client has no assigned stock available to sell. Please request stock first.
                    </div>
                @else
                    <select id="sale_product_id" name="product_id" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-xs">
                        <option value="">-- Choose Product --</option>
                        @foreach($clientAssignedProducts as $cp)
                            <option value="{{ $cp->product_id }}">
                                {{ $cp->product->name }} (Available: {{ $cp->remaining_qty }} units &bull; LKR {{ number_format($cp->selling_price, 2) }})
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <!-- Quantity -->
            <div>
                <label for="sale_qty" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Quantity Sold *</label>
                <input type="number" id="sale_qty" name="quantity" min="1" value="1" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-xs">
            </div>

            <!-- Customer Name -->
            <div>
                <label for="sale_customer" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Customer / Farmer Name <span class="text-gray-400 font-normal lowercase">(optional)</span></label>
                <input type="text" id="sale_customer" name="customer_name" placeholder="e.g. Ruwan Perera" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-xs">
            </div>

            <button type="submit" {{ $clientAssignedProducts->isEmpty() ? 'disabled' : '' }} class="touch-btn w-full py-3.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Record Retail Sale</span>
            </button>
        </form>

    </div>
</div>

<!-- ==================================================== -->
<!-- 4. QUICK PAYMENT MODAL (PRE-FILLED ACTIVE CLIENT) -->
<!-- ==================================================== -->
<div id="payment-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl max-h-[90vh] flex flex-col shadow-2xl animate-slideUp">
        
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-black text-gray-900">Collect Payment</h3>
                    <p class="text-[11px] text-gray-400 font-medium">From: <strong class="text-gray-800">{{ $selectedClient->business_name ?? $selectedClient->name ?? 'Client' }}</strong></p>
                </div>
            </div>
            <button type="button" onclick="closePaymentModal()" class="p-2 text-gray-400 hover:text-gray-700 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('ref.payments.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4 overflow-y-auto max-h-[75vh]">
            @csrf
            <input type="hidden" name="source" value="dashboard">
            <input type="hidden" name="client_id" value="{{ $selectedClient->id ?? '' }}" required>

            <!-- Amount -->
            <div>
                <label for="pay_amount" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Collected Amount (LKR) *</label>
                <input type="number" step="0.01" id="pay_amount" name="amount" min="1" required placeholder="e.g. 25000" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 shadow-xs">
            </div>

            <!-- Payment Method -->
            <div>
                <label for="pay_method" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Payment Method *</label>
                <select id="pay_method" name="payment_method" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 shadow-xs">
                    <option value="cash">Cash Collection</option>
                    <option value="bank_transfer">Bank Transfer / Deposit</option>
                    <option value="cheque">Cheque</option>
                    <option value="credit_card">Card</option>
                    <option value="online">Online Payment</option>
                </select>
            </div>

            <!-- Reference / Bank -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="pay_ref" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Ref / Slip #</label>
                    <input type="text" id="pay_ref" name="reference_number" placeholder="REF-XXXX" class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-900 shadow-xs">
                </div>
                <div>
                    <label for="pay_bank" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Bank Name</label>
                    <input type="text" id="pay_bank" name="bank_name" placeholder="e.g. Commercial Bank" class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-900 shadow-xs">
                </div>
            </div>

            <!-- Receipt Photo -->
            <div>
                <label for="pay_screenshot" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Slip Photo / Receipt</label>
                <input type="file" id="pay_screenshot" name="payment_screenshot" accept="image/*,.pdf" class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-700 shadow-xs file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-extrabold file:bg-emerald-50 file:text-[#1E8E3E]">
            </div>

            <button type="submit" class="touch-btn w-full py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Submit Collected Payment</span>
            </button>
        </form>

    </div>
</div>

<!-- ==================================================== -->
<!-- JAVASCRIPT CONTROLLERS FOR MOBILE MODALS & SEARCH -->
<!-- ==================================================== -->
<script>
    function openClientModal() {
        document.getElementById('client-modal').classList.remove('hidden');
        setTimeout(() => document.getElementById('client-search-input')?.focus(), 100);
    }

    function closeClientModal() {
        document.getElementById('client-modal').classList.add('hidden');
    }

    function filterClients(query) {
        const q = query.toLowerCase().trim();
        const items = document.querySelectorAll('.client-list-item');
        items.forEach(item => {
            const data = item.getAttribute('data-search') || '';
            if (data.includes(q)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function selectClientAndRedirect(clientId) {
        window.location.href = `{{ route('ref.dashboard') }}?client_id=${clientId}`;
    }

    function openStockModal() {
        document.getElementById('stock-modal').classList.remove('hidden');
    }

    function closeStockModal() {
        document.getElementById('stock-modal').classList.add('hidden');
    }

    function openSalesModal() {
        document.getElementById('sales-modal').classList.remove('hidden');
    }

    function closeSalesModal() {
        document.getElementById('sales-modal').classList.add('hidden');
    }

    function openPaymentModal() {
        document.getElementById('payment-modal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('payment-modal').classList.add('hidden');
    }
</script>
@endsection
