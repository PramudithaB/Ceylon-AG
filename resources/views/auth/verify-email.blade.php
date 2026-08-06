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

            <!-- Envelope Icon Badge -->
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center mb-4 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Verify Your Email
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 font-medium mt-2 max-w-sm leading-relaxed">
                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
            </p>
        </div>

        <!-- Verification Link Sent Status Banner -->
        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800 flex items-start gap-3">
                <svg class="w-5 h-5 text-[#1E8E3E] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>A new verification link has been sent to the email address you provided during registration.</span>
            </div>
        @endif

        <div class="space-y-4 pt-2">
            <!-- Resend Verification Form -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                    class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-xl shadow-emerald-700/25 hover:shadow-emerald-700/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Resend Verification Email</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Log Out Form -->
            <form method="POST" action="{{ route('logout') }}" class="text-center pt-2">
                @csrf
                <button type="submit" class="w-full py-3 px-4 rounded-2xl text-xs font-bold text-gray-600 hover:text-rose-600 hover:bg-rose-50 transition-all border border-gray-200">
                    Log Out of Account
                </button>
            </form>
        </div>

    </div>
</x-guest-layout>
