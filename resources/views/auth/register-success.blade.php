<x-guest-layout>
    <div class="text-center space-y-6">
        <!-- Icon -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 border border-amber-200 dark:border-amber-800 shadow-lg shadow-amber-500/10">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300">
                Pending Approval
            </span>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Registration Received</h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">
                Thank you for registering your business with <span class="font-semibold text-slate-900 dark:text-slate-100">{{ config('app.name', 'Ceylon AG') }}</span>. 
                We have sent a verification email to your address.
            </p>
        </div>

        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-left space-y-2 text-xs text-slate-600 dark:text-slate-400">
            <div class="flex items-start space-x-2">
                <span class="text-emerald-500 font-bold">✓</span>
                <p><strong class="text-slate-800 dark:text-slate-200">Email Verification:</strong> Please check your inbox and click the verification link.</p>
            </div>
            <div class="flex items-start space-x-2">
                <span class="text-amber-500 font-bold">⏳</span>
                <p><strong class="text-slate-800 dark:text-slate-200">Admin Activation:</strong> An administrator must review and approve your account before you can log in.</p>
            </div>
        </div>

        <div class="pt-4">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/30 transition-all">
                Return to Login
            </a>
        </div>
    </div>
</x-guest-layout>
