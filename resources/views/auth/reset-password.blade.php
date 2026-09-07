<x-guest-layout>
    <div class="glass-auth-card p-8 sm:p-10 rounded-[28px] shadow-2xl relative overflow-hidden" x-data="{
        password: '',
        get strength() {
            let score = 0;
            if (this.password.length > 7) score += 25;
            if (this.password.match(/[A-Z]/)) score += 25;
            if (this.password.match(/[0-9]/)) score += 25;
            if (this.password.match(/[^A-Za-z0-9]/)) score += 25;
            return score;
        },
        get strengthLabel() {
            if (this.strength <= 25) return 'Weak';
            if (this.strength <= 50) return 'Fair';
            if (this.strength <= 75) return 'Good';
            return 'Strong';
        },
        get strengthColor() {
            if (this.strength <= 25) return 'bg-rose-500';
            if (this.strength <= 50) return 'bg-amber-500';
            if (this.strength <= 75) return 'bg-emerald-400';
            return 'bg-[#1E8E3E]';
        }
    }">
        
        <!-- Top Centered Logo & Header -->
        <div class="flex flex-col items-center justify-center text-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-white p-2 shadow-xl shadow-emerald-700/10 mb-4 hover:scale-105 transition-transform duration-300 border border-emerald-100/80 flex items-center justify-center overflow-hidden shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG" class="w-full h-full object-contain">
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Reset Password
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">
                Choose a strong new password for your account
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
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
                        value="{{ old('email', $request->email) }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="auth-input block w-full pl-11 pr-4 py-3.5 bg-white/70 border border-gray-200 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-rose-600 font-semibold" />
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    New Password
                </label>
                <div class="relative rounded-2xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input id="password" 
                        type="password" 
                        name="password" 
                        x-model="password"
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••" 
                        class="auth-input block w-full pl-11 pr-4 py-3.5 bg-white/70 border border-gray-200 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                    />
                </div>
                
                <!-- Password Strength Meter -->
                <div class="mt-2.5" x-show="password.length > 0">
                    <div class="flex items-center justify-between text-[11px] font-bold text-gray-600 mb-1">
                        <span>Strength:</span>
                        <span x-text="strengthLabel" :class="{
                            'text-rose-600': strength <= 25,
                            'text-amber-600': strength > 25 && strength <= 50,
                            'text-emerald-600': strength > 50 && strength <= 75,
                            'text-[#1E8E3E]': strength > 75
                        }"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full transition-all duration-300" :class="strengthColor" :style="'width: ' + strength + '%'"></div>
                    </div>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-600 font-semibold" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Confirm New Password
                </label>
                <div class="relative rounded-2xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <input id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••" 
                        class="auth-input block w-full pl-11 pr-4 py-3.5 bg-white/70 border border-gray-200 rounded-2xl text-sm font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                    />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-rose-600 font-semibold" />
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-xl shadow-emerald-700/25 hover:shadow-emerald-700/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Reset Password</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>

    </div>
</x-guest-layout>
