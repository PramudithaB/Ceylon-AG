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

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 antialiased">
    <div class="min-h-screen flex">
        <!-- Ref Sidebar Component -->
        <x-ref.sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 sm:ml-64 flex flex-col min-h-screen">
            <!-- Top Navbar -->
            <header class="h-16 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-30 flex items-center justify-between px-6">
                <div class="flex items-center gap-3">
                    <!-- Mobile Hamburger -->
                    <button type="button" onclick="document.getElementById('ref-sidebar').classList.toggle('-translate-x-full')" class="sm:hidden text-slate-400 hover:text-white p-2 rounded-lg bg-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-lg font-bold text-white tracking-tight">{{ $header ?? 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Quick Role Badge -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/30 text-emerald-400 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sales Representative (Ref)
                    </div>

                    <!-- User Profile Dropdown / Avatar -->
                    <a href="{{ route('ref.profile.edit') }}" class="flex items-center gap-2.5 hover:opacity-90 transition-opacity">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-emerald-500/40">
                        <span class="hidden md:inline-block text-xs font-medium text-slate-200">{{ auth()->user()->name }}</span>
                    </a>
                </div>
            </header>

            <!-- Flash Message Banner -->
            <x-flash-messages />

            <!-- Dynamic Content -->
            <main class="flex-1 p-6 space-y-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="py-4 px-6 border-t border-slate-900 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} Ceylon AG. Sales Representative Dashboard Portal.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
