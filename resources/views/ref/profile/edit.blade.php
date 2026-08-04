<x-ref-layout>
    <x-slot name="header">
        Representative Profile & Password Settings
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- 1. Profile Information Form -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl space-y-6">
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">Personal Profile Information</h2>
                <p class="text-xs text-slate-400 mt-1">Update your sales representative details and contact information</p>
            </div>

            <form method="POST" action="{{ route('ref.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PATCH')

                <!-- Photo preview -->
                <div class="flex items-center gap-4 pb-4 border-b border-slate-800">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-emerald-500/40">
                    <div>
                        <label for="profile_photo" class="block text-xs font-semibold text-slate-300 mb-1">Update Profile Photo</label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-emerald-400 hover:file:bg-slate-700">
                        @error('profile_photo')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- First & Last Name -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">First Name *</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? $user->name) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        @error('first_name')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        @error('last_name')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Phone & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Phone Number *</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        @error('phone')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Email Address *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        @error('email')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Street Address *</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    @error('address')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- District & Province -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="district" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">District *</label>
                        <input type="text" id="district" name="district" value="{{ old('district', $user->district) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        @error('district')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="province" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Province *</label>
                        <input type="text" id="province" name="province" value="{{ old('province', $user->province) }}" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                        @error('province')
                            <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Change Password Form -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl space-y-6">
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">Security & Change Password</h2>
                <p class="text-xs text-slate-400 mt-1">Ensure your account is using a strong security password</p>
            </div>

            <form method="POST" action="{{ route('ref.profile.password') }}" class="space-y-4 max-w-xl">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Current Password *</label>
                    <input type="password" id="current_password" name="current_password" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    @error('current_password')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">New Password *</label>
                    <input type="password" id="password" name="password" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    @error('password')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Confirm New Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    @error('password_confirmation')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex justify-start">
                    <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-ref-layout>
