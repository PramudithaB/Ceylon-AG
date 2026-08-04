<x-ref-layout>
    <x-slot name="header">
        Notifications & Activity Feed
    </x-slot>

    <!-- Header Action Bar -->
    <div class="flex items-center justify-between bg-slate-900/90 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">System Alerts & Notifications</h2>
            <p class="text-xs text-slate-400 mt-0.5">Stay updated on client approvals, stock request status, and payments</p>
        </div>

        <form method="POST" action="{{ route('ref.notifications.read-all') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-emerald-400 rounded-xl transition-all border border-emerald-500/20">
                Mark All as Read
            </button>
        </form>
    </div>

    <!-- Notifications List -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl divide-y divide-slate-800/80">
        @forelse($notifications as $notification)
            <div class="p-5 flex items-start gap-4 hover:bg-slate-800/30 transition-colors {{ $notification->read_at ? 'opacity-70' : 'border-l-4 border-l-emerald-500 bg-emerald-950/10' }}">
                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white">{{ $notification->data['title'] ?? 'System Notification' }}</h3>
                        <span class="text-[11px] text-slate-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-slate-300 mt-1">{{ $notification->data['message'] ?? $notification->data['body'] ?? 'No detail provided.' }}</p>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-slate-500 text-xs">
                No notifications received yet.
            </div>
        @endforelse
    </div>

    <div class="p-4 bg-slate-900/90 border border-slate-800 rounded-2xl">
        {{ $notifications->links() }}
    </div>
</x-ref-layout>
