<header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20 shadow-sm transition-colors">
    <!-- Left Section: Toggle Sidebar & Page Title -->
    <div class="flex items-center space-x-4">
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-colors lg:hidden">
            <span class="sr-only">Open Sidebar</span>
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        @if (isset($header))
            <div>{{ $header }}</div>
        @else
            <div>
                <h1 class="text-lg font-bold text-slate-800 dark:text-slate-100">Dashboard</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Welcome back, {{ Auth::user()->name ?? 'User' }}</p>
            </div>
        @endif
    </div>

    <!-- Right Section: Search, Notifications, User Menu -->
    <div class="flex items-center space-x-3">
        <!-- Quick Search Input Mockup -->
        <div class="hidden md:flex items-center relative">
            <svg class="w-4 h-4 text-slate-400 absolute left-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" 
                   placeholder="Search..." 
                   class="pl-9 pr-4 py-1.5 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 w-48 transition-all">
        </div>

        <!-- Notification Bell Trigger -->
        <button class="relative p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-white transition-colors focus:outline-none">
            <span class="sr-only">Notifications</span>
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
        </button>

        <!-- User Profile Dropdown (Alpine) -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" 
                    class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-semibold shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                </div>
                <span class="hidden md:inline-block text-xs font-semibold text-slate-700 dark:text-slate-200">
                    {{ Auth::user()->name ?? 'Admin' }}
                </span>
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 rounded-2xl bg-white dark:bg-slate-800 shadow-xl border border-slate-100 dark:border-slate-700 py-2 z-50">
                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700">
                    <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile Settings
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-xs text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        <svg class="w-4 h-4 mr-2.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
