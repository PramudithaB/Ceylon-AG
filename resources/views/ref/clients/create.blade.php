<x-ref-layout>
    <x-slot name="header">
        Register Client
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-5">
        <!-- Back Link -->
        <a href="{{ route('ref.dashboard') }}" class="text-xs font-bold text-gray-500 hover:text-[#1E8E3E] inline-flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Ref Dashboard</span>
        </a>

        <!-- Form Card -->
        <div class="ref-card rounded-3xl p-5 sm:p-8 space-y-6 bg-white border border-gray-100 shadow-xs">
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 bg-emerald-100 text-[#1E8E3E] font-extrabold text-[10px] uppercase rounded-full tracking-wider">
                        Field Registration
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Register Client</h2>
                <p class="text-xs text-gray-500 font-medium mt-1">Register a new client shop and automatically link to your portfolio.</p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs space-y-1 shadow-xs">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('ref.clients.store') }}" class="space-y-4">
                @csrf

                <!-- First & Last Name -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="first_name" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">First Name *</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="e.g. Ruwan" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                        @error('first_name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="e.g. Silva" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                        @error('last_name')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Business Name -->
                <div>
                    <label for="business_name" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Business Name *</label>
                    <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" required placeholder="e.g. Silva Agro Center" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                    @error('business_name')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="phone" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="07X XXXXXXX" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                        @error('phone')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Email Address *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="silva@agro.lk" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                        @error('email')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Province & District (Dependent Dropdown) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Province -->
                    <div>
                        <label for="province" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Province *</label>
                        <select id="province" name="province" required onchange="handleRefProvinceChange(this.value)" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                            <option value="" disabled {{ old('province') ? '' : 'selected' }}>Select Province</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov }}" {{ old('province') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                            @endforeach
                        </select>
                        @error('province')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- District (Dependent) -->
                    <div>
                        <label for="district" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">District *</label>
                        <select id="district" name="district" required {{ old('province') ? '' : 'disabled' }} class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-bold text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                            <option value="" disabled {{ old('district') ? '' : 'selected' }}>
                                {{ old('province') ? 'Select District' : 'Select Province First' }}
                            </option>
                            @if(old('province'))
                                @foreach(\App\Support\Locations::getDistrictsByProvince(old('province')) as $dist)
                                    <option value="{{ $dist }}" {{ old('district') == $dist ? 'selected' : '' }}>{{ $dist }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('district')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Shop Address -->
                <div>
                    <label for="address" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Shop Address *</label>
                    <textarea id="address" name="address" rows="2" required placeholder="Street address, building number, town" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password & Confirm Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="password" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Initial Password *</label>
                        <input type="password" id="password" name="password" required placeholder="Minimum 8 characters" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                        @error('password')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-extrabold text-gray-700 uppercase tracking-wider mb-1.5">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat password" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium text-gray-900 focus:border-[#1E8E3E] focus:ring-2 focus:ring-[#1E8E3E]/20 shadow-xs">
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="touch-btn w-full py-4 bg-[#1E8E3E] hover:bg-emerald-700 text-white font-black text-sm rounded-2xl shadow-md transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span>REGISTER CLIENT</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const refProvinceDistrictsMap = @json(\App\Support\Locations::getHierarchy());

        function handleRefProvinceChange(provinceName) {
            const districtSelect = document.getElementById('district');
            if (!districtSelect) return;

            districtSelect.innerHTML = '';
            const districts = refProvinceDistrictsMap[provinceName] || [];

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

                districtSelect.disabled = false;
            } else {
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select Province First';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                districtSelect.appendChild(defaultOption);
                districtSelect.disabled = true;
            }
        }
    </script>
</x-ref-layout>
