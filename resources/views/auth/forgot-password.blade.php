<x-guest-layout>
    <div class="glass-auth-card p-8 sm:p-10 rounded-[28px] shadow-2xl relative overflow-hidden">
        
        <!-- Top Centered Logo & Header -->
        <div class="flex flex-col items-center justify-center text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] p-0.5 shadow-lg shadow-emerald-700/20 mb-4 hover:scale-105 transition-transform duration-300">
                <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG" class="w-11 h-11 object-contain">
                    @else
                        <svg class="w-8 h-8 text-[#1E8E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    @endif
                </div>
            </div>

            <!-- Key/Lock Badge Icon -->
            <div class="w-12 h-12 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"/></svg>
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Forgot Your Password?
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-2 max-w-sm leading-relaxed">
                No problem. Enter your account email address below and we will send you a password reset link.
            </p>
        </div>

        <!-- Session Status Alert -->
        <x-auth-session-status class="mb-6 text-xs font-semibold text-emerald-700 bg-emerald-50 p-4 rounded-2xl border border-emerald-200" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <!-- Email Address Field -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Email Address
                </label>
                <div class="relative rounded-2xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    </div>
                    <input id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        placeholder="dealer@ceylonag.lk" 
                        class="auth-input block w-full pl-11 pr-4 py-3.5 bg-white/70 border border-gray-200 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-600 font-semibold" />
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-xl shadow-emerald-700/25 hover:shadow-emerald-700/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Email Password Reset Link</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <a href="{{ route('login') }}" class="text-xs font-bold text-gray-600 hover:text-[#1E8E3E] transition-colors inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Remember your password? Back to Login</span>
            </a>
        </div>

    </div>
</x-guest-layout>
