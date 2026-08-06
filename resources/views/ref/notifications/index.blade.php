<x-ref-layout>
    <x-slot name="header">
        Notifications & Activity Feed
    </x-slot>

    <!-- Header & Action Card -->
    <div class="ref-card rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">System Alerts & Notifications</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Stay updated on client approvals, stock request status, and payments</p>
        </div>

        <form method="POST" action="{{ route('ref.notifications.read-all') }}">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-emerald-50 hover:bg-[#1E8E3E] text-[#1E8E3E] hover:text-white font-extrabold text-xs rounded-2xl transition-all border border-emerald-200 shadow-sm">
                Mark All as Read
            </button>
        </form>
    </div>

    <!-- Notifications List Card -->
    <div class="ref-card rounded-3xl divide-y divide-gray-100 overflow-hidden">
        @forelse($notifications as $notification)
            <div class="p-6 flex items-start gap-4 hover:bg-emerald-50/30 transition-colors {{ $notification->read_at ? 'opacity-60' : 'bg-emerald-50/60 border-l-4 border-l-[#1E8E3E]' }}">
                <div class="w-10 h-10 rounded-2xl bg-white border border-emerald-200 flex items-center justify-center text-[#1E8E3E] shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-gray-900">{{ $notification->data['title'] ?? 'System Notification' }}</h3>
                        <span class="text-[11px] text-gray-400 font-medium">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-gray-600 font-medium mt-1 leading-relaxed">{{ $notification->data['message'] ?? $notification->data['body'] ?? 'No details provided.' }}</p>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-400 font-medium text-xs">
                No notifications received yet.
            </div>
        @endforelse
    </div>

    <div class="ref-card rounded-3xl p-4">
        {{ $notifications->links() }}
    </div>
</x-ref-layout>
