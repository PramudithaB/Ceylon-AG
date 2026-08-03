<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Stock Allocation Requests</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Review client partner stock requests and authorize warehouse dispatches</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ rejectModalOpen: false, rejectUrl: '', reqCode: '' }">

        <!-- Status Filter Tabs -->
        <div class="flex items-center space-x-2 border-b border-slate-200 dark:border-slate-800 pb-2">
            <a href="{{ route('admin.stock-requests.index', ['status' => 'all']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold {{ $status === 'all' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                All Requests
            </a>
            <a href="{{ route('admin.stock-requests.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold {{ $status === 'pending' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                ⏳ Pending Verification
            </a>
            <a href="{{ route('admin.stock-requests.index', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                ✓ Approved
            </a>
            <a href="{{ route('admin.stock-requests.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-xl text-xs font-semibold {{ $status === 'rejected' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                ✗ Rejected
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Request Code #</th>
                            <th class="py-3.5 px-6">Client Partner</th>
                            <th class="py-3.5 px-6">Product</th>
                            <th class="py-3.5 px-6">Requested Qty</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6">Date</th>
                            <th class="py-3.5 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                        @forelse($stockRequests as $req)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-4 px-6 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $req->request_number }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $req->client->name ?? 'N/A' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $req->client->business_name ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800 dark:text-slate-200">
                                    {{ $req->product->name ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-6 font-black text-slate-900 dark:text-white text-sm">
                                    {{ $req->requested_quantity }} units
                                </td>
                                <td class="py-4 px-6">
                                    @if($req->isPending())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300">
                                            ⏳ Pending
                                        </span>
                                    @elseif($req->isApproved())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                            ✓ Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300">
                                            ✗ Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-slate-500">
                                    {{ $req->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    @if($req->isPending())
                                        <form method="POST" action="{{ route('admin.stock-requests.approve', $req->id) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Approve this stock request?')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-[11px]">
                                                Approve
                                            </button>
                                        </form>

                                        <button @click="rejectModalOpen = true; rejectUrl = '{{ route('admin.stock-requests.reject', $req->id) }}'; reqCode = '{{ $req->request_number }}'" class="px-2.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-semibold text-[11px]">
                                            Reject
                                        </button>
                                    @else
                                        <span class="text-[11px] text-slate-400">Reviewed by {{ $req->reviewer->name ?? 'Admin' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                    No stock allocation requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($stockRequests->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $stockRequests->links() }}
                </div>
            @endif
        </div>

        <!-- Rejection Modal -->
        <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Reject Stock Request <span x-text="reqCode"></span></h3>
                <form :action="rejectUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="rejection_reason" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Rejection Reason</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950" placeholder="State reason for rejecting stock request..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600 text-white">
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>
