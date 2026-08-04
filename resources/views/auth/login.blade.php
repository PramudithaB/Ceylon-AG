<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-white via-green-50 to-white px-4 py-10">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-green-100 p-8">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Ceylon Agro Marketing" class="w-28 h-28 object-contain">
            </div>

            <!-- Heading -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-green-800">
                    Ceylon Agro Marketing
                </h1>

                <p class="mt-2 text-gray-500">
                    Dealer Management System
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-5">
                    <x-input-label for="email" :value="__('Email Address')" class="text-green-800 font-semibold" />

                    <x-text-input id="email"
                        class="block mt-2 w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('Password')" class="text-green-800 font-semibold" />

                    <x-text-input id="password"
                        class="block mt-2 w-full rounded-xl border-gray-300 focus:border-green-600 focus:ring-green-600"
                        type="password" name="password" required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between mt-4">

                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-green-700 shadow-sm focus:ring-green-600"
                            name="remember">

                        <span class="ml-2 text-sm text-gray-600">
                            Remember Me
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-green-700 hover:text-green-900 font-medium">
                            Forgot Password?
                        </a>
                    @endif

                </div>

                <!-- Login Button -->
                <div class="mt-8">
                    <button type="submit"
                        class="w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-3 rounded-xl transition duration-300 shadow-lg">

                        Login

                    </button>
                </div>

            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-400">
                © {{ date('Y') }} Ceylon Agro Marketing (Pvt) Ltd.
            </div>

        </div>
    </div>
</x-guest-layout>