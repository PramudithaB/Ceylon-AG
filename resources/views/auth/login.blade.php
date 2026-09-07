<x-guest-layout>
    <div class="glass-auth-card p-8 sm:p-10 rounded-[28px] shadow-2xl relative overflow-hidden">
        
        <!-- Top Centered Logo -->
        <div class="flex flex-col items-center justify-center text-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-white p-2 shadow-xl shadow-emerald-700/10 mb-4 hover:scale-105 transition-transform duration-300 border border-emerald-100/80 flex items-center justify-center overflow-hidden shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG" class="w-full h-full object-contain">
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Welcome Back
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">
                Sign in to continue to Ceylon AG Dealer Management Platform
            </p>
        </div>

        <!-- Session Status Alert -->
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                        autocomplete="username"
                        placeholder="dealer@ceylonag.lk" 
                        class="auth-input block w-full pl-11 pr-4 py-3.5 bg-white/70 border border-gray-200 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-600 font-semibold" />
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#1E8E3E] hover:text-[#0F4D22] transition-colors">
                            Forgot Password?
                        </a>
                    @endif
                </div>
                <div class="relative rounded-2xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••" 
                        class="auth-input block w-full pl-11 pr-4 py-3.5 bg-white/70 border border-gray-200 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-600 font-semibold" />
            </div>

            <!-- Remember Me Option -->
            <div class="flex items-center">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" 
                        type="checkbox" 
                        name="remember" 
                        class="rounded-lg border-gray-300 text-[#1E8E3E] focus:ring-[#1E8E3E] w-4 h-4 shadow-sm"
                    >
                    <span class="ml-2.5 text-xs font-semibold text-gray-600">
                        Keep me signed in on this device
                    </span>
                </label>
            </div>

            <!-- Primary Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-xl shadow-emerald-700/25 hover:shadow-emerald-700/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Sign In to Account</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>

        <!-- Register Link Banner -->
        @if (Route::has('register'))
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs font-medium text-gray-500">
                    Don't have a dealer account yet? 
                    <a href="{{ route('register') }}" class="font-extrabold text-[#1E8E3E] hover:text-[#0F4D22] underline underline-offset-4 ml-1">
                        Register Account
                    </a>
                </p>
            </div>
        @endif

    </div>
</x-guest-layout>