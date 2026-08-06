<x-ref-layout>
    <x-slot name="header">
        Company Announcements & Directives
    </x-slot>

    <!-- Header Card -->
    <div class="ref-card rounded-3xl p-6">
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Sales Representative Announcements</h2>
        <p class="text-xs text-gray-500 font-medium mt-0.5">Official circulars, sales incentive rules, and operational directives</p>
    </div>

    <!-- Announcements List -->
    <div class="space-y-5">
        @foreach($announcements as $announcement)
            <div class="ref-card rounded-3xl p-6 sm:p-8 space-y-3 relative overflow-hidden group hover:border-emerald-300 transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-[#1E8E3E] text-[10px] font-extrabold uppercase rounded-full tracking-wider">
                            {{ $announcement['category'] }}
                        </span>
                        <span class="text-xs font-bold text-gray-500">&bull; {{ $announcement['author'] }}</span>
                    </div>

                    <span class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($announcement['date'])->format('M d, Y') }}</span>
                </div>

                <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight group-hover:text-[#1E8E3E] transition-colors">{{ $announcement['title'] }}</h3>
                <p class="text-xs sm:text-sm text-gray-600 font-medium leading-relaxed">{{ $announcement['content'] }}</p>
            </div>
        @endforeach
    </div>
</x-ref-layout>
