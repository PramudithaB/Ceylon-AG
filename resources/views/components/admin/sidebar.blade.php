<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-100 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col justify-between shadow-xl border-r border-slate-800">
    <div>
        <!-- Brand Header -->
        <div class="flex items-center justify-between h-16 px-6 bg-slate-950/60 border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    C
                </div>
                <div>
                    <span class="text-base font-bold tracking-tight text-white group-hover:text-emerald-400 transition-colors">Ceylon AG</span>
                    <span class="block text-[10px] text-slate-400 uppercase tracking-widest font-semibold">Admin Panel</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="px-4 py-6 space-y-1 overflow-y-auto max-h-[calc(100vh-8rem)]">
            <div class="px-3 pb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Main Navigation</div>

            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route(['admin.dashboard', 'dashboard'], 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin')))
                <a href="{{ route('admin.clients.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.clients.*', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Client Management</span>
                    </div>
                    @php $pendingCount = \App\Models\User::where('role', \App\Models\User::ROLE_CLIENT)->where('status', \App\Models\User::STATUS_PENDING)->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-500 text-slate-950">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.products.*', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Products Catalog</span>
                    </div>
                    @php $lowStockCount = \App\Models\Product::whereColumn('stock_quantity', '<=', 'minimum_stock')->count(); @endphp
                    @if($lowStockCount > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-rose-500 text-white">{{ $lowStockCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.product-assignments.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.product-assignments.*', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span>Product Assignments</span>
                    </div>
                </a>

                <a href="{{ route('admin.quotations.dashboard') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.quotations.*', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Quotations</span>
                    </div>
                </a>

                <a href="{{ route('admin.stock-requests.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.stock-requests.*', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                    </svg>
                    <span>Stock Requests</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.reports.*', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Master Reports & Analytics</span>
                </a>

                <a href="{{ route('admin.payments.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.payments.*', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Payments Verification</span>
                    </div>
                    @php $pendingPaymentCount = \App\Models\Payment::where('status', \App\Models\Payment::STATUS_PENDING)->count(); @endphp
                    @if($pendingPaymentCount > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-500 text-slate-950">{{ $pendingPaymentCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.sales.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.sales.index', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Global Sales</span>
                    </div>
                </a>

                <a href="{{ route('admin.sales.reports') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.sales.reports', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Master Reports</span>
                    </div>
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('admin.categories.*', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>Categories</span>
                </a>
            @endif

            <div class="pt-6 px-3 pb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">System Setup</div>

            <a href="{{ route('profile.edit') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ is_active_route('profile.edit', 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30', 'text-slate-300 hover:bg-slate-800/70 hover:text-white') }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Account Profile</span>
            </a>
        </nav>
    </div>

    <!-- User Mini Card at Bottom of Sidebar -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/40">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-sm font-semibold text-emerald-400">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@ceylonag.com' }}</p>
            </div>
        </div>
    </div>
</aside>
