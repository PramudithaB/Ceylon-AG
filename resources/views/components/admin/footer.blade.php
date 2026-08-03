<footer class="mt-auto py-4 px-6 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs text-slate-500 dark:text-slate-400 flex flex-col md:flex-row items-center justify-between gap-2 transition-colors">
    <div>
        &copy; {{ date('Y') }} <span class="font-semibold text-slate-700 dark:text-slate-200">{{ config('app.name', 'Ceylon AG') }}</span>. All rights reserved.
    </div>
    <div class="flex items-center space-x-4">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
            System Online
        </span>
        <span class="text-slate-400 dark:text-slate-500">v12.0.0</span>
    </div>
</footer>
