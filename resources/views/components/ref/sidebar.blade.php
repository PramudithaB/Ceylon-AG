<aside id="ref-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-white/95 backdrop-blur-xl border-r border-emerald-100/80 text-gray-700 flex flex-col justify-between shadow-xl shadow-emerald-950/5">
    
    <!-- Sidebar Header / Logo -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-emerald-100/60">
        <a href="{{ route('ref.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-11 h-11 rounded-2xl bg-white p-1 shadow-md shadow-emerald-700/15 group-hover:scale-105 transition-transform duration-300 border border-emerald-100 flex items-center justify-center overflow-hidden shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col">
                <span class="text-base font-extrabold tracking-tight text-gray-900 group-hover:text-[#1E8E3E] transition-colors">Ceylon AG</span>
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-[#1E8E3E]">Sales Representative</span>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button type="button" onclick="document.getElementById('ref-sidebar').classList.add('-translate-x-full')" class="sm:hidden text-gray-400 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-4">
        
        <!-- SECTION: DASHBOARD -->
        <div>
            <span class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block mb-1">Overview</span>
            <a href="{{ route('ref.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.dashboard') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- SECTION: CLIENTS -->
        <div>
            <span class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block mb-1">Clients</span>
            <a href="{{ route('ref.clients.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.clients.*') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Assigned Clients</span>
            </a>
        </div>

        <!-- SECTION: TRANSACTIONS -->
        <div class="space-y-1">
            <span class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block mb-1">Transactions</span>
            
            <!-- Stock Requests -->
            <a href="{{ route('ref.stock-requests.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.stock-requests.*') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Stock Requests</span>
            </a>

            <!-- Sales -->
            <a href="{{ route('ref.sales.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.sales.index') || request()->routeIs('ref.sales.create') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Territory Sales</span>
            </a>

            <!-- Payments -->
            <a href="{{ route('ref.payments.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.payments.*') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Client Payments</span>
            </a>

            <!-- Products Catalog -->
            <a href="{{ route('ref.products.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.products.*') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Products Catalog</span>
            </a>
        </div>

        <!-- SECTION: REPORTS & SETTINGS -->
        <div class="space-y-1">
            <span class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 block mb-1">Reports & Info</span>
            
            <a href="{{ route('ref.sales.reports') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.sales.reports') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Reports & Analytics</span>
            </a>

            <a href="{{ route('ref.notifications.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.notifications.*') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span>Notifications</span>
                </div>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="bg-[#1E8E3E] text-white font-extrabold text-[10px] px-2 py-0.5 rounded-full">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>

            <a href="{{ route('ref.announcements.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.announcements.*') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c.41 0 .789.248.953.626l2.147 5.15M18 13h1a2 2 0 002-2V9a2 2 0 00-2-2h-1m0 6v-6" />
                </svg>
                <span>Announcements</span>
            </a>

            <a href="{{ route('ref.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('ref.profile.*') ? 'bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white shadow-md shadow-emerald-700/20' : 'text-gray-600 hover:bg-emerald-50/80 hover:text-[#1E8E3E]' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profile Settings</span>
            </a>
        </div>
    </div>

    <!-- Sidebar Footer Profile -->
    <div class="p-4 border-t border-emerald-100/60 bg-emerald-50/40">
        <div class="flex items-center gap-3 mb-3 px-1">
            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover border-2 border-[#1E8E3E]/30">
            <div class="overflow-hidden">
                <p class="text-xs font-extrabold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-bold bg-white text-gray-700 hover:bg-rose-50 hover:text-rose-600 transition-colors border border-gray-200/80 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Log Out
            </button>
        </form>
    </div>
</aside>
