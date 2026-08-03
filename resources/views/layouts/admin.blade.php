<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ceylon AG') }} - Admin Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-950" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex flex-col lg:flex-row bg-slate-50 dark:bg-slate-950">
            <!-- Sidebar -->
            <x-admin.sidebar />

            <!-- Overlay for Mobile Sidebar -->
            <div x-show="sidebarOpen" 
                 @click="sidebarOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-20 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
                <!-- Top Navigation -->
                <x-admin.navbar>
                    @if (isset($header))
                        <x-slot name="header">
                            {{ $header }}
                        </x-slot>
                    @endif
                </x-admin.navbar>

                <!-- Page Content -->
                <main class="flex-1 p-4 lg:p-8 max-w-7xl w-full mx-auto">
                    <!-- Global Flash Messages -->
                    <x-flash-messages />

                    <!-- Main Slot -->
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <x-admin.footer />
            </div>
        </div>
    </body>
</html>
