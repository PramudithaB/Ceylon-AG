<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | {{ config('app.name', 'Ceylon AG') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-100 bg-slate-950 flex items-center justify-center p-6 selection:bg-emerald-500 selection:text-white">
    <div class="max-w-md w-full text-center space-y-8">
        <!-- Error Badge & Icon -->
        <div class="relative inline-flex items-center justify-center">
            <div class="absolute -inset-4 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full opacity-20 blur-xl"></div>
            <div class="relative w-24 h-24 rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl flex items-center justify-center text-emerald-400">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Text Content -->
        <div class="space-y-3">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                Error 404
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Page Not Found</h1>
            <p class="text-sm text-slate-400 max-w-sm mx-auto">
                The route or page you are searching for might have been moved, renamed, or doesn't exist.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-center gap-4 pt-2">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200 hover:-translate-y-0.5">
                Return Home
            </a>
            <button onclick="window.history.back()" 
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-800 transition-all duration-200">
                Go Back
            </button>
        </div>
    </div>
</body>
</html>
