<x-ref-layout>
    <x-slot name="header">
        Representative Profile & Password Settings
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- 1. Profile Information Form Card -->
        <div class="ref-card rounded-3xl p-6 sm:p-10 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Personal Profile Information</h2>
                <p class="text-xs text-gray-500 font-medium mt-1">Update your sales representative contact details and avatar photo</p>
            </div>

            <form method="POST" action="{{ route('ref.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PATCH')

                <!-- Photo preview -->
                <div class="flex items-center gap-5 pb-5 border-b border-gray-100">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-4 border-emerald-100 shadow-md">
                    <div>
                        <label for="profile_photo" class="block text-xs font-bold text-gray-700 mb-1.5">Update Profile Avatar</label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-emerald-50 file:text-[#1E8E3E] hover:file:bg-emerald-100">
                        @error('profile_photo')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- First & Last Name -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">First Name *</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? $user->name) }}" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                        @error('first_name')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                        @error('last_name')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Phone & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Phone Number *</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                        @error('phone')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                        @error('email')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Street Address -->
                <div>
                    <label for="address" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Street Address *</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    @error('address')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- District & Province -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="district" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">District *</label>
                        <input type="text" id="district" name="district" value="{{ old('district', $user->district) }}" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                        @error('district')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="province" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Province *</label>
                        <input type="text" id="province" name="province" value="{{ old('province', $user->province) }}" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                        @error('province')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-md hover:opacity-95 transition-all">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Security & Change Password Card -->
        <div class="ref-card rounded-3xl p-6 sm:p-10 space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Security & Update Password</h2>
                <p class="text-xs text-gray-500 font-medium mt-1">Ensure your account is protected with a strong, secure password</p>
            </div>

            <form method="POST" action="{{ route('ref.profile.password') }}" class="space-y-4 max-w-xl">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Current Password *</label>
                    <input type="password" id="current_password" name="current_password" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    @error('current_password')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">New Password *</label>
                    <input type="password" id="password" name="password" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    @error('password')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Confirm New Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="auth-input block w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:bg-white">
                    @error('password_confirmation')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex justify-start">
                    <button type="submit" class="px-6 py-3 bg-gray-900 hover:bg-[#1E8E3E] text-white font-extrabold text-xs rounded-2xl shadow-md transition-all">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-ref-layout>
