<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Client Management Console</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">View, search, filter, approve, activate, deactivate, and edit business client accounts</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openAssignModal()" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/20 transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Assign Client to Ref
                </button>
                <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Client
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Search & Filter Controls -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.clients.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Search Clients</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by name, business, NIC, phone, email..." class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 pl-9 pr-3 py-2 text-slate-800 dark:text-slate-200">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- District Filter -->
                <div>
                    <label for="district" class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">District</label>
                    <select id="district" name="district" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 focus:border-emerald-500 focus:ring-emerald-500 py-2 px-3 text-slate-800 dark:text-slate-200">
                        <option value="all" {{ ($filters['district'] ?? 'all') === 'all' ? 'selected' : '' }}>All Districts</option>
                        @foreach($districts as $district)
                            <option value="{{ $district }}" {{ ($filters['district'] ?? '') === $district ? 'selected' : '' }}>{{ $district }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="w-full py-2 px-4 rounded-xl text-xs font-semibold bg-slate-900 text-white dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 transition-all flex items-center justify-center">
                        Filter Results
                    </button>
                    @if(!empty($filters['search']) || ($filters['status'] ?? 'all') !== 'all' || ($filters['district'] ?? 'all') !== 'all')
                        <a href="{{ route('admin.clients.index') }}" class="py-2 px-3 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center space-x-2 border-b border-slate-200 dark:border-slate-800 pb-3 overflow-x-auto">
            <a href="{{ route('admin.clients.index', array_merge($filters, ['status' => 'all'])) }}" 
               class="flex items-center space-x-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($filters['status'] ?? 'all') === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <span>All Clients</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-slate-700/20 text-current">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('admin.clients.index', array_merge($filters, ['status' => 'pending'])) }}" 
               class="flex items-center space-x-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($filters['status'] ?? '') === 'pending' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40' }}">
                <span>Pending Approval</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-current">{{ $counts['pending'] }}</span>
            </a>
            <a href="{{ route('admin.clients.index', array_merge($filters, ['status' => 'active'])) }}" 
               class="flex items-center space-x-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($filters['status'] ?? '') === 'active' || ($filters['status'] ?? '') === 'approved' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }}">
                <span>Active Accounts</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-current">{{ $counts['approved'] + $counts['active'] }}</span>
            </a>
            <a href="{{ route('admin.clients.index', array_merge($filters, ['status' => 'deactivated'])) }}" 
               class="flex items-center space-x-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($filters['status'] ?? '') === 'deactivated' ? 'bg-slate-600 text-white shadow-md shadow-slate-600/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <span>Deactivated</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-current">{{ $counts['deactivated'] }}</span>
            </a>
            <a href="{{ route('admin.clients.index', array_merge($filters, ['status' => 'rejected'])) }}" 
               class="flex items-center space-x-2 px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($filters['status'] ?? '') === 'rejected' ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20' : 'text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40' }}">
                <span>Rejected</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-current">{{ $counts['rejected'] }}</span>
            </a>
        </div>

        <!-- Clients Table Container -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Client Profile</th>
                            <th class="py-3.5 px-6">NIC & Contact</th>
                            <th class="py-3.5 px-6">Assigned Ref</th>
                            <th class="py-3.5 px-6">Location</th>
                            <th class="py-3.5 px-6">Registration Date</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($clients as $client)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- Client Profile -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 shadow-sm" src="{{ $client->profile_photo_url }}" alt="{{ $client->full_name }}">
                                        <div>
                                            <a href="{{ route('admin.clients.show', $client) }}" class="font-bold text-slate-900 dark:text-white hover:text-emerald-500 transition-colors">
                                                {{ $client->full_name }}
                                            </a>
                                            <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">{{ $client->business_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- NIC & Contact -->
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-slate-800 dark:text-slate-200">NIC: {{ $client->nic ?? 'N/A' }}</div>
                                    <div class="text-slate-500 dark:text-slate-400 mt-0.5">{{ $client->phone }}</div>
                                    <div class="text-slate-400 dark:text-slate-500 text-[11px]">{{ $client->email }}</div>
                                </td>

                                <!-- Assigned Ref -->
                                <td class="py-4 px-6">
                                    @if($client->salesRep)
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                                <svg class="w-3 h-3 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                {{ $client->salesRep->name }}
                                            </span>
                                            <button type="button" onclick="openAssignModalForClient({{ $client->id }}, '{{ addslashes($client->full_name) }}', {{ $client->ref_id ?? 'null' }})" title="Change Assigned Ref" class="p-1 rounded text-slate-400 hover:text-indigo-600">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs text-slate-400 italic">Unassigned</span>
                                            <button type="button" onclick="openAssignModalForClient({{ $client->id }}, '{{ addslashes($client->full_name) }}', null)" class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                                + Assign Ref
                                            </button>
                                        </div>
                                    @endif
                                </td>

                                <!-- Location -->
                                <td class="py-4 px-6">
                                    <div class="text-slate-800 dark:text-slate-200 font-medium">{{ $client->district ?? 'N/A' }}, {{ $client->province ?? '' }}</div>
                                    <div class="text-slate-400 dark:text-slate-500 truncate max-w-xs text-[11px]">{{ $client->address }}</div>
                                </td>

                                <!-- Registration Date -->
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-400">
                                    {{ format_date($client->created_at, 'M d, Y') }}
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    @if($client->isPending())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                            Pending Approval
                                        </span>
                                    @elseif($client->isDeactivated())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                            Deactivated
                                        </span>
                                    @elseif($client->isRejected())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            Active / Approved
                                        </span>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- Profile View Button -->
                                        <a href="{{ route('admin.clients.show', $client) }}" title="View Profile" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.clients.edit', $client) }}" title="Edit Client" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Status Action Toggles -->
                                        @if($client->isPending())
                                            <form method="POST" action="{{ route('admin.clients.approve', $client) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Approve Account" class="p-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-600 hover:bg-emerald-200 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.clients.reject', $client) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Reject Registration" class="p-1.5 rounded-lg bg-rose-100 dark:bg-rose-950 text-rose-600 hover:bg-rose-200 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @elseif($client->isDeactivated())
                                            <form method="POST" action="{{ route('admin.clients.activate', $client) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Reactivate Account" class="p-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-600 hover:bg-emerald-200 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.clients.deactivate', $client) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Deactivate Account" class="p-1.5 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-300 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    No client registrations found matching your query.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($clients->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- ASSIGN CLIENT TO REF MODAL -->
    <div id="assignRefModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-5 animate-scaleUp">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Assign Client to Ref</h3>
                </div>
                <button type="button" onclick="closeAssignModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.clients.assign-ref') }}" class="space-y-4">
                @csrf

                <!-- Client Selector -->
                <div>
                    <label for="modal_client_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Select Client *
                    </label>
                    <select id="modal_client_id" name="client_id" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2.5 px-3 text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" disabled selected>-- Select Client --</option>
                        @foreach($allClients as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->full_name }} ({{ $c->business_name ?? 'Individual' }}) &bull; [Current: {{ $c->salesRep ? $c->salesRep->name : 'Unassigned' }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Ref Selector -->
                <div>
                    <label for="modal_ref_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                        Select Sales Representative (Ref)
                    </label>
                    <select id="modal_ref_id" name="ref_id" class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 py-2.5 px-3 text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Unassign (No Ref) --</option>
                        @foreach($refs as $ref)
                            <option value="{{ $ref->id }}">
                                {{ $ref->full_name }} ({{ $ref->email }}{{ $ref->phone ? ' &bull; ' . $ref->phone : '' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Select a Ref to link, or select 'Unassign' to remove link.</p>
                </div>

                <div class="pt-3 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeAssignModal()" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/20 transition-all">
                        Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal() {
            document.getElementById('assignRefModal').classList.remove('hidden');
        }

        function openAssignModalForClient(clientId, clientName, refId) {
            const clientSelect = document.getElementById('modal_client_id');
            const refSelect = document.getElementById('modal_ref_id');

            if (clientSelect) {
                clientSelect.value = clientId;
            }
            if (refSelect) {
                refSelect.value = refId !== null ? refId : '';
            }

            openAssignModal();
        }

        function closeAssignModal() {
            document.getElementById('assignRefModal').classList.add('hidden');
        }

        // Close on background click
        document.getElementById('assignRefModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssignModal();
            }
        });
    </script>
</x-admin-layout>
