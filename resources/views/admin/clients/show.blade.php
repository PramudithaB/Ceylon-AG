<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.clients.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Client Profile Details</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">View complete account record and manage authorization status</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-amber-500 hover:bg-amber-400 text-white shadow-md transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Client
                </a>

                @role('Super Admin')
                    <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Are you sure you want to permanently delete this client account?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600 hover:bg-rose-500 text-white shadow-md transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Client
                        </button>
                    </form>
                @endrole
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Client Hero Profile Header Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                <!-- Profile Photo -->
                <img class="w-24 h-24 rounded-2xl object-cover border-2 border-emerald-500 shadow-lg shadow-emerald-500/10" src="{{ $client->profile_photo_url }}" alt="{{ $client->full_name }}">

                <div class="flex-1 text-center sm:text-left space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ $client->full_name }}</h2>
                            <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $client->business_name }}</p>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            @if($client->isPending())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                                    Pending Approval
                                </span>
                            @elseif($client->isDeactivated())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                                    <span class="w-2 h-2 rounded-full bg-slate-400 mr-2"></span>
                                    Deactivated
                                </span>
                            @elseif($client->isRejected())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span>
                                    Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                                    Active Account
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-800/80">
                        <div><strong class="text-slate-700 dark:text-slate-300">NIC:</strong> {{ $client->nic ?? 'N/A' }}</div>
                        <div>•</div>
                        <div><strong class="text-slate-700 dark:text-slate-300">Registration Date:</strong> {{ format_date($client->created_at, 'F d, Y \a\t h:i A') }}</div>
                        <div>•</div>
                        <div><strong class="text-slate-700 dark:text-slate-300">Email Verified:</strong> {{ $client->email_verified_at ? format_date($client->email_verified_at, 'M d, Y') : 'Unverified' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Management Bar -->
        <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-white">Account Status Controls</h3>
                <p class="text-xs text-slate-400">Change authorization state to permit or restrict client access to Ceylon AG services</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if(!$client->isApproved())
                    <form method="POST" action="{{ route('admin.clients.approve', $client) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md transition-all">
                            Approve Account
                        </button>
                    </form>
                @endif

                @if(!$client->isActive() && $client->isApproved())
                    <form method="POST" action="{{ route('admin.clients.activate', $client) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md transition-all">
                            Activate Account
                        </button>
                    </form>
                @endif

                @if(!$client->isDeactivated() && ($client->isApproved() || $client->isActive()))
                    <form method="POST" action="{{ route('admin.clients.deactivate', $client) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition-all">
                            Deactivate Account
                        </button>
                    </form>
                @endif

                @if(!$client->isRejected())
                    <form method="POST" action="{{ route('admin.clients.reject', $client) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600/80 hover:bg-rose-600 text-white border border-rose-500/30 transition-all">
                            Reject Account
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Detailed Fields Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Business & Personal Info Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M13 16h.01M13 12h.01M14 8h-4" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Identity & Business Information</h3>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Full Name</dt>
                        <dd class="font-bold text-slate-900 dark:text-white mt-0.5">{{ $client->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Business Name</dt>
                        <dd class="font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $client->business_name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">NIC Number</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $client->nic ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Account Role</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">
                            {{ $client->roles->pluck('name')->implode(', ') ?: 'Client' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Registration Date</dt>
                        <dd class="text-slate-700 dark:text-slate-300 mt-0.5">{{ format_date($client->created_at, 'M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Account Status</dt>
                        <dd class="text-slate-700 dark:text-slate-300 mt-0.5 uppercase font-bold text-[11px]">{{ $client->status }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Contact & Location Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Contact & Address Details</h3>
                </div>

                <dl class="space-y-3 text-xs">
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Phone Number</dt>
                        <dd class="font-semibold text-slate-900 dark:text-white mt-0.5">{{ $client->phone ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Email Address</dt>
                        <dd class="font-semibold text-slate-900 dark:text-white mt-0.5">{{ $client->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Street Address</dt>
                        <dd class="text-slate-700 dark:text-slate-300 mt-0.5">{{ $client->address ?? 'N/A' }}</dd>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">District</dt>
                            <dd class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $client->district ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 dark:text-slate-500 uppercase tracking-wider text-[10px] font-semibold">Province</dt>
                            <dd class="font-semibold text-slate-800 dark:text-slate-200 mt-0.5">{{ $client->province ?? 'N/A' }}</dd>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-admin-layout>
