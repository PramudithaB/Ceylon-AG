<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.clients.show', $client) }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Edit Client: {{ $client->full_name }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Update business information, status, or reset credentials</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.clients.update', $client) }}" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Profile Photo Preview & Upload -->
            <div>
                <x-input-label for="photo" :value="__('Profile Photo')" />
                <div class="mt-2 flex items-center space-x-4">
                    <img class="w-14 h-14 rounded-full object-cover border-2 border-emerald-500" src="{{ $client->profile_photo_url }}" alt="{{ $client->full_name }}">
                    <div class="flex-1">
                        <input type="file" id="photo" name="photo" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-950 dark:file:text-emerald-300 hover:file:bg-emerald-100 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-1">Leave empty to keep existing profile image.</p>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('photo')" class="mt-1" />
            </div>

            <!-- Name Fields Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" class="block mt-1 w-full text-xs" type="text" name="first_name" :value="old('first_name', $client->first_name)" required autofocus />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" class="block mt-1 w-full text-xs" type="text" name="last_name" :value="old('last_name', $client->last_name)" required />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
                </div>
            </div>

            <!-- Business Name & NIC Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="business_name" :value="__('Business Name')" />
                    <x-text-input id="business_name" class="block mt-1 w-full text-xs" type="text" name="business_name" :value="old('business_name', $client->business_name)" required />
                    <x-input-error :messages="$errors->get('business_name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="nic" :value="__('NIC Number')" />
                    <x-text-input id="nic" class="block mt-1 w-full text-xs" type="text" name="nic" :value="old('nic', $client->nic)" required />
                    <x-input-error :messages="$errors->get('nic')" class="mt-1" />
                </div>
            </div>

            <!-- Phone & Email Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" class="block mt-1 w-full text-xs" type="text" name="phone" :value="old('phone', $client->phone)" required />
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email" class="block mt-1 w-full text-xs" type="email" name="email" :value="old('email', $client->email)" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>
            </div>

            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Street Address')" />
                <x-text-input id="address" class="block mt-1 w-full text-xs" type="text" name="address" :value="old('address', $client->address)" required />
                <x-input-error :messages="$errors->get('address')" class="mt-1" />
            </div>

            <!-- Province, District, Status Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="province" :value="__('Province *')" />
                    <select id="province" name="province" onchange="handleAdminEditProvinceChange(this.value)" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2 px-3" required>
                        @foreach($provinces as $province)
                            <option value="{{ $province }}" {{ old('province', $client->province) == $province ? 'selected' : '' }}>{{ $province }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('province')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="district" :value="__('District *')" />
                    <select id="district" name="district" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2 px-3" required>
                        @php
                            $currentProvince = old('province', $client->province);
                            $validDistricts = \App\Support\Locations::getDistrictsByProvince($currentProvince);
                        @endphp
                        @foreach($validDistricts as $district)
                            <option value="{{ $district }}" {{ old('district', $client->district) == $district ? 'selected' : '' }}>{{ $district }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('district')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="status" :value="__('Account Status')" />
                    <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2 px-3" required>
                        <option value="approved" {{ old('status', $client->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ old('status', $client->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="deactivated" {{ old('status', $client->status) == 'deactivated' ? 'selected' : '' }}>Deactivated</option>
                        <option value="rejected" {{ old('status', $client->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="ref_id" :value="__('Assigned Sales Representative (Ref)')" />
                    <select id="ref_id" name="ref_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-xs py-2 px-3">
                        <option value="">-- No Assigned Ref (Unassigned) --</option>
                        @foreach($refs as $ref)
                            <option value="{{ $ref->id }}" {{ old('ref_id', $client->ref_id) == $ref->id ? 'selected' : '' }}>
                                {{ $ref->full_name }} ({{ $ref->email }}{{ $ref->phone ? ' • ' . $ref->phone : '' }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('ref_id')" class="mt-1" />
                </div>
            </div>

            <!-- Optional New Password Fields -->
            <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-2">Change Password (Optional)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="password" :value="__('New Password')" />
                        <x-text-input id="password" class="block mt-1 w-full text-xs" type="password" name="password" placeholder="Leave blank to keep unchanged" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full text-xs" type="password" name="password_confirmation" placeholder="Confirm new password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.clients.show', $client) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                    Cancel
                </a>
                <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">
                    Update Client Profile
                </x-primary-button>
            </div>
        </form>

        <script>
            const adminEditProvinceDistrictsMap = @json(\App\Support\Locations::getHierarchy());

            function handleAdminEditProvinceChange(provinceName) {
                const districtSelect = document.getElementById('district');
                if (!districtSelect) return;

                districtSelect.innerHTML = '';
                const districts = adminEditProvinceDistrictsMap[provinceName] || [];

                if (districts.length > 0) {
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select District';
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    districtSelect.appendChild(defaultOption);

                    districts.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d;
                        opt.textContent = d;
                        districtSelect.appendChild(opt);
                    });
                }
            }
        </script>
    </div>
</x-admin-layout>
