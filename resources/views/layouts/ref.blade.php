<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sales Representative Portal' }} - {{ config('app.name', 'Ceylon AG') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts & Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                primary: '#FFFFFF',
                                secondary: '#1E8E3E',
                                accent: '#6CC24A',
                                dark: '#1F2937',
                                bg: '#F8FAF7'
                            }
                        },
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAF7;
            color: #1F2937;
            -webkit-tap-highlight-color: transparent;
        }

        .ref-card {
            background: #FFFFFF;
            border: 1px solid rgba(30, 142, 62, 0.1);
            box-shadow: 0 4px 20px -2px rgba(30, 142, 62, 0.05), 0 0 0 1px rgba(30, 142, 62, 0.02);
            transition: all 0.2s ease-in-out;
        }

        .gradient-text-green {
            background: linear-gradient(135deg, #1E8E3E 0%, #6CC24A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .touch-btn {
            min-height: 48px;
            touch-action: manipulation;
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(30, 142, 62, 0.2);
            border-radius: 4px;
        }
    </style>
</head>
<body class="h-full bg-[#F8FAF7] text-gray-900 antialiased selection:bg-emerald-600 selection:text-white">
    <div class="min-h-screen flex">
        
        <!-- Sidebar Component (Desktop) -->
        <x-ref.sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 sm:ml-64 flex flex-col min-h-screen">
            
            <!-- Sticky Top Header Bar -->
            <header class="h-16 sm:h-20 bg-white/90 backdrop-blur-xl border-b border-emerald-100/60 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8 shadow-xs">
                
                <!-- Left: Mobile Toggle & Page Title -->
                <div class="flex items-center gap-3">
                    <button type="button" onclick="document.getElementById('ref-sidebar').classList.toggle('-translate-x-full')" class="sm:hidden text-gray-600 hover:text-[#1E8E3E] p-2 rounded-xl bg-gray-100/80 border border-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-sm sm:text-lg font-extrabold text-gray-900 tracking-tight leading-tight">{{ $header ?? 'Ref Portal' }}</h1>
                        <p class="text-[10px] text-gray-400 font-medium">Sales Representative</p>
                    </div>
                </div>

                <!-- Right: Active Client Quick Pill / Badges / Profile Dropdown -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- Role Status Pill (Desktop) -->
                    <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-[#1E8E3E] text-xs font-bold shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-[#1E8E3E] animate-pulse"></span>
                        <span>Ref Active</span>
                    </div>

                    <!-- Notification Bell -->
                    <a href="{{ route('ref.notifications.index') }}" class="relative p-2 text-gray-500 hover:text-[#1E8E3E] rounded-xl hover:bg-emerald-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#1E8E3E] ring-2 ring-white"></span>
                        @endif
                    </a>

                    <!-- Profile Avatar -->
                    <a href="{{ route('ref.profile.edit') }}" class="flex items-center gap-2 p-1 rounded-full hover:bg-emerald-50 transition-colors">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border-2 border-[#1E8E3E]/40 shadow-xs">
                        <span class="hidden lg:inline-block text-xs font-bold text-gray-800 pr-1 truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                    </a>
                </div>
            </header>

            <!-- Flash Messages Notification Banner -->
            <x-flash-messages />

            <!-- Main Page Content (With bottom padding for Mobile Navigation Bar) -->
            <main class="flex-1 p-4 sm:p-8 pb-28 sm:pb-8 space-y-6 max-w-7xl mx-auto w-full">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            <!-- Footer (Desktop) -->
            <footer class="hidden sm:block py-4 px-8 border-t border-emerald-100/60 text-center text-xs font-medium text-gray-400">
                &copy; {{ date('Y') }} Ceylon Agro Marketing (Pvt) Ltd. Sales Representative Enterprise System.
            </footer>

            <!-- FIXED MOBILE BOTTOM NAVIGATION BAR (Phones 360px - 414px) -->
            <nav class="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-xl border-t border-emerald-100/80 shadow-2xl flex items-center justify-around py-2 px-1">
                
                <!-- 1. Dashboard -->
                <a href="{{ route('ref.dashboard') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('ref.dashboard') ? 'text-[#1E8E3E] font-black' : 'text-gray-500 hover:text-[#1E8E3E]' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-[10px]">Home</span>
                </a>

                <!-- 2. Clients -->
                <a href="{{ route('ref.clients.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('ref.clients.*') ? 'text-[#1E8E3E] font-black' : 'text-gray-500 hover:text-[#1E8E3E]' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-[10px]">Clients</span>
                </a>

                <!-- 3. Stock -->
                <a href="{{ route('ref.stock-requests.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('ref.stock-requests.*') ? 'text-[#1E8E3E] font-black' : 'text-gray-500 hover:text-[#1E8E3E]' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="text-[10px]">Stock</span>
                </a>

                <!-- 4. Sales -->
                <a href="{{ route('ref.sales.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('ref.sales.*') ? 'text-[#1E8E3E] font-black' : 'text-gray-500 hover:text-[#1E8E3E]' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="text-[10px]">Sales</span>
                </a>

                <!-- 5. Payments -->
                <a href="{{ route('ref.payments.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all {{ request()->routeIs('ref.payments.*') ? 'text-[#1E8E3E] font-black' : 'text-gray-500 hover:text-[#1E8E3E]' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-[10px]">Payments</span>
                </a>

            </nav>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
