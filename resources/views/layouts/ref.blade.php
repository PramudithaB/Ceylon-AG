<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        }

        .ref-card {
            background: #FFFFFF;
            border: 1px solid rgba(30, 142, 62, 0.1);
            box-shadow: 0 10px 30px -5px rgba(30, 142, 62, 0.05), 0 0 0 1px rgba(30, 142, 62, 0.02);
            transition: all 0.25s ease-in-out;
        }

        .ref-card:hover {
            box-shadow: 0 20px 40px -10px rgba(30, 142, 62, 0.1);
        }

        .gradient-text-green {
            background: linear-gradient(135deg, #1E8E3E 0%, #6CC24A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="h-full bg-[#F8FAF7] text-gray-900 antialiased selection:bg-emerald-600 selection:text-white">
    <div class="min-h-screen flex">
        
        <!-- Sidebar Component -->
        <x-ref.sidebar />

        <!-- Main Content Wrapper -->
        <div class="flex-1 sm:ml-64 flex flex-col min-h-screen">
            
            <!-- Sticky Glass Top Navigation Bar -->
            <header class="h-20 bg-white/80 backdrop-blur-xl border-b border-emerald-100/60 sticky top-0 z-30 flex items-center justify-between px-6 sm:px-8">
                
                <!-- Left: Mobile Toggle & Page Title -->
                <div class="flex items-center gap-3">
                    <button type="button" onclick="document.getElementById('ref-sidebar').classList.toggle('-translate-x-full')" class="sm:hidden text-gray-500 hover:text-[#1E8E3E] p-2 rounded-xl bg-gray-100/80 border border-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">{{ $header ?? 'Dashboard' }}</h1>
                        <p class="text-[10px] text-gray-400 font-medium">Sales Representative Portal</p>
                    </div>
                </div>

                <!-- Right: Quick Badges & Profile Dropdown -->
                <div class="flex items-center gap-4">
                    
                    <!-- Role Status Pill -->
                    <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-[#1E8E3E] text-xs font-bold shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[#1E8E3E] animate-pulse"></span>
                        <span>Territory Ref Active</span>
                    </div>

                    <!-- Notification Quick Bell Icon -->
                    <a href="{{ route('ref.notifications.index') }}" class="relative p-2 text-gray-500 hover:text-[#1E8E3E] rounded-xl hover:bg-emerald-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 rounded-full bg-[#1E8E3E] ring-2 ring-white"></span>
                        @endif
                    </a>

                    <!-- Profile Quick Link -->
                    <a href="{{ route('ref.profile.edit') }}" class="flex items-center gap-3 p-1 rounded-full hover:bg-emerald-50 transition-colors">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover border-2 border-[#1E8E3E]/40 shadow-sm">
                        <span class="hidden md:inline-block text-xs font-bold text-gray-800 pr-2">{{ auth()->user()->name }}</span>
                    </a>
                </div>
            </header>

            <!-- Flash Messages Notification Banner -->
            <x-flash-messages />

            <!-- Main Page Content -->
            <main class="flex-1 p-6 sm:p-8 space-y-6">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            <!-- Footer -->
            <footer class="py-5 px-8 border-t border-emerald-100/60 text-center text-xs font-medium text-gray-400">
                &copy; {{ date('Y') }} Ceylon Agro Marketing (Pvt) Ltd. Sales Representative Enterprise System.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
