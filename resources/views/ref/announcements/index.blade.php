<x-ref-layout>
    <x-slot name="header">
        Company Announcements & Directives
    </x-slot>

    <!-- Header -->
    <div class="bg-slate-900/90 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <h2 class="text-xl font-bold text-white tracking-tight">Sales Representative Announcements</h2>
        <p class="text-xs text-slate-400 mt-0.5">Official circulars, sales incentive rules, and operational announcements</p>
    </div>

    <!-- Announcements Cards -->
    <div class="space-y-4">
        @foreach($announcements as $announcement)
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-3 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase rounded-full tracking-wider">
                            {{ $announcement['category'] }}
                        </span>
                        <span class="text-xs font-semibold text-slate-400">&bull; {{ $announcement['author'] }}</span>
                    </div>

                    <span class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($announcement['date'])->format('M d, Y') }}</span>
                </div>

                <h3 class="text-lg font-bold text-white tracking-tight">{{ $announcement['title'] }}</h3>
                <p class="text-xs text-slate-300 leading-relaxed">{{ $announcement['content'] }}</p>
            </div>
        @endforeach
    </div>
</x-ref-layout>
