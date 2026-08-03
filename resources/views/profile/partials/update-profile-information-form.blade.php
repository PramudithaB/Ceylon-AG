<section>
    <header>
        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
            {{ __('Profile & Business Information') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            {{ __("Update your account profile photo, personal information, business contact, and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Photo Upload -->
        <div>
            <x-input-label for="photo" :value="__('Profile Photo')" />
            <div class="mt-2 flex items-center space-x-4">
                <img class="w-16 h-16 rounded-full object-cover border-2 border-emerald-500 shadow-md" src="{{ $user->profile_photo_url }}" alt="{{ $user->full_name }}">
                
                <div class="space-y-2">
                    <input type="file" id="photo" name="photo" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-300 hover:file:bg-emerald-100 cursor-pointer">
                    <p class="text-[11px] text-slate-400">PNG, JPG, or WEBP up to 2MB.</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <!-- Name Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full text-xs" :value="old('first_name', $user->first_name)" autocomplete="given-name" />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full text-xs" :value="old('last_name', $user->last_name)" autocomplete="family-name" />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <!-- Business & NIC Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="business_name" :value="__('Business Name')" />
                <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full text-xs" :value="old('business_name', $user->business_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
            </div>

            <div>
                <x-input-label for="nic" :value="__('NIC Number')" />
                <x-text-input id="nic" name="nic" type="text" class="mt-1 block w-full text-xs bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-not-allowed" :value="$user->nic" readonly />
                <p class="text-[10px] text-slate-400 mt-1">NIC number is locked for verification purposes.</p>
            </div>
        </div>

        <!-- Phone & Email Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full text-xs" :value="old('phone', $user->phone)" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full text-xs" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-xs text-amber-600 dark:text-amber-400 flex items-center">
                            <span>Your email address is unverified.</span>
                            <button form="send-verification" class="underline ml-2 text-xs text-slate-600 dark:text-slate-300 hover:text-slate-900 font-medium">
                                {{ __('Re-send verification email') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 font-medium text-xs text-emerald-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Street Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full text-xs" :value="old('address', $user->address)" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- District & Province Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="district" :value="__('District')" />
                <select id="district" name="district" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-xs py-2 px-3">
                    <option value="" disabled>Select District</option>
                    @foreach($districts ?? [] as $district)
                        <option value="{{ $district }}" {{ old('district', $user->district) == $district ? 'selected' : '' }}>{{ $district }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('district')" />
            </div>

            <div>
                <x-input-label for="province" :value="__('Province')" />
                <select id="province" name="province" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-xs py-2 px-3">
                    <option value="" disabled>Select Province</option>
                    @foreach($provinces ?? [] as $province)
                        <option value="{{ $province }}" {{ old('province', $user->province) == $province ? 'selected' : '' }}>{{ $province }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('province')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100 dark:border-slate-800">
            <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">{{ __('Save Profile Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-semibold text-emerald-600"
                >{{ __('Profile updated successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
