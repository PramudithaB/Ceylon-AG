<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white">Client Account Scaffolding</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Fill in your business details to register for a Ceylon AG client account.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name Fields Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full text-xs" type="text" name="first_name" :value="old('first_name')" required autofocus placeholder="John" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
            </div>

            <!-- Last Name -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full text-xs" type="text" name="last_name" :value="old('last_name')" required placeholder="Doe" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
            </div>
        </div>

        <!-- Business Name & NIC Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Business Name -->
            <div>
                <x-input-label for="business_name" :value="__('Business Name')" />
                <x-text-input id="business_name" class="block mt-1 w-full text-xs" type="text" name="business_name" :value="old('business_name')" required placeholder="Ceylon Enterprises Ltd" />
                <x-input-error :messages="$errors->get('business_name')" class="mt-1" />
            </div>

            <!-- NIC -->
            <div>
                <x-input-label for="nic" :value="__('NIC Number')" />
                <x-text-input id="nic" class="block mt-1 w-full text-xs" type="text" name="nic" :value="old('nic')" required placeholder="199012345678 or 901234567V" />
                <x-input-error :messages="$errors->get('nic')" class="mt-1" />
            </div>
        </div>

        <!-- Phone & Email Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Phone -->
            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" class="block mt-1 w-full text-xs" type="text" name="phone" :value="old('phone')" required placeholder="+94 77 123 4567" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" class="block mt-1 w-full text-xs" type="email" name="email" :value="old('email')" required placeholder="client@business.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Street Address')" />
            <x-text-input id="address" class="block mt-1 w-full text-xs" type="text" name="address" :value="old('address')" required placeholder="123 Main Street, Suite 400" />
            <x-input-error :messages="$errors->get('address')" class="mt-1" />
        </div>

        <!-- District & Province Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- District -->
            <div>
                <x-input-label for="district" :value="__('District')" />
                <select id="district" name="district" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-xs py-2 px-3" required>
                    <option value="" disabled selected>Select District</option>
                    @foreach($districts as $district)
                        <option value="{{ $district }}" {{ old('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('district')" class="mt-1" />
            </div>

            <!-- Province -->
            <div>
                <x-input-label for="province" :value="__('Province')" />
                <select id="province" name="province" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-xs py-2 px-3" required>
                    <option value="" disabled selected>Select Province</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('province')" class="mt-1" />
            </div>
        </div>

        <!-- Password & Password Confirmation Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full text-xs" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full text-xs" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800 mt-6">
            <a class="underline text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}">
                {{ __('Already registered? Log in') }}
            </a>

            <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">
                {{ __('Register Account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
