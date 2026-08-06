<x-guest-layout>
    <div class="glass-auth-card p-6 sm:p-10 rounded-[28px] shadow-2xl relative overflow-hidden">
        
        <!-- Top Centered Logo & Header -->
        <div class="flex flex-col items-center justify-center text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] p-0.5 shadow-lg shadow-emerald-700/20 mb-3 hover:scale-105 transition-transform duration-300">
                <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG" class="w-9 h-9 object-contain">
                    @else
                        <svg class="w-7 h-7 text-[#1E8E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    @endif
                </div>
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                Create Account
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">
                Join Ceylon AG Dealer Management Platform
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Section 1: Personal Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        First Name
                    </label>
                    <div class="relative rounded-2xl shadow-sm">
                        <input id="first_name" 
                            type="text" 
                            name="first_name" 
                            value="{{ old('first_name') }}" 
                            required 
                            autofocus 
                            placeholder="John" 
                            class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('first_name')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Last Name
                    </label>
                    <div class="relative rounded-2xl shadow-sm">
                        <input id="last_name" 
                            type="text" 
                            name="last_name" 
                            value="{{ old('last_name') }}" 
                            required 
                            placeholder="Doe" 
                            class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('last_name')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>
            </div>

            <!-- Section 2: Business & NIC Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Business Name -->
                <div>
                    <label for="business_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Business Name
                    </label>
                    <div class="relative rounded-2xl shadow-sm">
                        <input id="business_name" 
                            type="text" 
                            name="business_name" 
                            value="{{ old('business_name') }}" 
                            required 
                            placeholder="Lanka Agro Enterprises" 
                            class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('business_name')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>

                <!-- NIC -->
                <div>
                    <label for="nic" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        NIC Number
                    </label>
                    <div class="relative rounded-2xl shadow-sm">
                        <input id="nic" 
                            type="text" 
                            name="nic" 
                            value="{{ old('nic') }}" 
                            required 
                            placeholder="199012345678 or 901234567V" 
                            class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('nic')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>
            </div>

            <!-- Section 3: Contact Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Phone Number
                    </label>
                    <div class="relative rounded-2xl shadow-sm">
                        <input id="phone" 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            required 
                            placeholder="+94 77 123 4567" 
                            class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Email Address
                    </label>
                    <div class="relative rounded-2xl shadow-sm">
                        <input id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="client@business.lk" 
                            class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>
            </div>

            <!-- Street Address -->
            <div>
                <label for="address" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                    Street Address
                </label>
                <div class="relative rounded-2xl shadow-sm">
                    <input id="address" 
                        type="text" 
                        name="address" 
                        value="{{ old('address') }}" 
                        required 
                        placeholder="123 Commercial Road, Kandy" 
                        class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                    />
                </div>
                <x-input-error :messages="$errors->get('address')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
            </div>

            <!-- District & Province -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- District -->
                <div>
                    <label for="district" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        District
                    </label>
                    <select id="district" 
                        name="district" 
                        required 
                        class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white"
                    >
                        <option value="" disabled selected>Select District</option>
                        @foreach($districts as $district)
                            <option value="{{ $district }}" {{ old('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('district')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Province
                    </label>
                    <select id="province" 
                        name="province" 
                        required 
                        class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white"
                    >
                        <option value="" disabled selected>Select Province</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('province')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>
            </div>

            <!-- Passwords -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Password
                    </label>
                    <div class="relative rounded-2xl shadow-sm">
                        <input id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password" 
                            placeholder="••••••••" 
                            class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Confirm Password
                    </label>
                    <div class="relative rounded-2xl shadow-sm">
                        <input id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password" 
                            placeholder="••••••••" 
                            class="auth-input block w-full px-4 py-3 bg-white/70 border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 placeholder-gray-400 focus:bg-white" 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-[11px] text-rose-600 font-semibold" />
                </div>
            </div>

            <!-- Submit Button & Actions -->
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-100 mt-6">
                <a href="{{ route('login') }}" class="text-xs font-bold text-gray-600 hover:text-[#1E8E3E] transition-colors">
                    Already registered? Log in
                </a>

                <button type="submit" 
                    class="w-full sm:w-auto px-8 py-3.5 rounded-2xl text-xs font-extrabold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-lg shadow-emerald-700/20 hover:shadow-emerald-700/35 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Register Account</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>

    </div>
</x-guest-layout>
