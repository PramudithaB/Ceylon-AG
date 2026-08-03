@if (session()->has('flash_message') || session('status') || session('success') || session('error') || session('warning') || session('info'))
    @php
        $flash = session('flash_message');
        $message = $flash['message'] ?? session('status') ?? session('success') ?? session('error') ?? session('warning') ?? session('info');
        $type = $flash['type'] ?? (session('error') ? 'error' : (session('warning') ? 'warning' : (session('info') ? 'info' : 'success')));
        
        $styles = match($type) {
            'error' => [
                'bg' => 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200',
                'icon_bg' => 'bg-rose-100 dark:bg-rose-900/60 text-rose-600 dark:text-rose-400',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'title' => 'Error'
            ],
            'warning' => [
                'bg' => 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200',
                'icon_bg' => 'bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
                'title' => 'Warning'
            ],
            'info' => [
                'bg' => 'bg-sky-50 dark:bg-sky-950/40 border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-200',
                'icon_bg' => 'bg-sky-100 dark:bg-sky-900/60 text-sky-600 dark:text-sky-400',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'title' => 'Notice'
            ],
            default => [
                'bg' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200',
                'icon_bg' => 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                'title' => 'Success'
            ],
        };
    @endphp

    <div x-data="{ show: true }" 
         x-show="show" 
         x-transition:leave="transition ease-in duration-300 transform" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95"
         class="mb-6 flex items-center justify-between p-4 rounded-xl border {{ $styles['bg'] }} shadow-sm transition-all duration-200"
         role="alert">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 p-2 rounded-lg {{ $styles['icon_bg'] }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    {!! $styles['icon'] !!}
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider opacity-75">{{ $styles['title'] }}</p>
                <p class="text-sm font-medium">{{ $message }}</p>
            </div>
        </div>
        <button @click="show = false" type="button" class="inline-flex rounded-lg p-1.5 focus:outline-none hover:bg-black/5 dark:hover:bg-white/10 transition-colors">
            <span class="sr-only">Dismiss</span>
            <svg class="w-4 h-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif
