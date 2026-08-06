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

            <!-- Security Shield Icon Badge -->
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Confirm Password
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-2 max-w-sm leading-relaxed">
                This is a secure area of Ceylon AG. Please confirm your password before continuing.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Current Password
                </label>
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

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                    class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-xl shadow-emerald-700/25 hover:shadow-emerald-700/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Confirm Security Access</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>

    </div>
</x-guest-layout>
