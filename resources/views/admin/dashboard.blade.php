<x-admin-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Admin Control Center</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">System overview and architecture performance summary</p>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: Revenue (Testing format_currency helper) -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Revenue</span>
                    <span class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">
                        {{ format_currency(1248500.50) }}
                    </h3>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1 inline-flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        +14.2% from last month
                    </p>
                </div>
            </div>

            <!-- Card 2: Active System Date (Testing format_date helper) -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">System Clock</span>
                    <span class="p-2 rounded-xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">
                        {{ format_date(now()) }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Timezone: {{ config('app.timezone') }}
                    </p>
                </div>
            </div>

            <!-- Card 3: Framework Version -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Laravel Core</span>
                    <span class="p-2 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">
                        v{{ Illuminate\Foundation\Application::VERSION }}
                    </h3>
                    <p class="text-xs text-purple-600 dark:text-purple-400 font-medium mt-1">
                        PHP v{{ PHP_VERSION }}
                    </p>
                </div>
            </div>

            <!-- Card 4: Queue Status -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Queue Driver</span>
                    <span class="p-2 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white uppercase">
                        {{ config('queue.default') }}
                    </h3>
                    <p class="text-xs text-teal-600 dark:text-teal-400 font-medium mt-1">
                        Ready for Background Workers
                    </p>
                </div>
            </div>
        </div>

        <!-- Flash Message & Error Testing Console -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-6">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Architecture Testing Console</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Test global helper functions, flash alerts, and error views</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.test-flash', ['type' => 'success']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white transition-all">
                    Trigger Success Alert
                </a>
                <a href="{{ route('admin.test-flash', ['type' => 'error']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-500 text-white transition-all">
                    Trigger Error Alert
                </a>
                <a href="{{ route('admin.test-flash', ['type' => 'warning']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-amber-600 hover:bg-amber-500 text-white transition-all">
                    Trigger Warning Alert
                </a>
                <a href="{{ route('admin.test-flash', ['type' => 'info']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-sky-600 hover:bg-sky-500 text-white transition-all">
                    Trigger Info Alert
                </a>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-3">Custom Error Pages Preview:</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ url('/test-error/403') }}" target="_blank" class="px-3.5 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors">
                        Preview 403 Forbidden
                    </a>
                    <a href="{{ url('/test-error/404') }}" target="_blank" class="px-3.5 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors">
                        Preview 404 Not Found
                    </a>
                    <a href="{{ url('/test-error/500') }}" target="_blank" class="px-3.5 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors">
                        Preview 500 Server Error
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
