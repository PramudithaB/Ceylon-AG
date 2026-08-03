<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    Stock Allocation Requests
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Request additional warehouse product quantities from Ceylon AG management</p>
            </div>
            <a href="{{ route('stock-requests.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Request Stock
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                                <th class="py-3.5 px-6">Request Code #</th>
                                <th class="py-3.5 px-6">Product</th>
                                <th class="py-3.5 px-6">Requested Qty</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6">Requested Date</th>
                                <th class="py-3.5 px-6">Notes / Rejection Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs">
                            @forelse($stockRequests as $req)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-6 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ $req->request_number }}
                                    </td>
                                    <td class="py-4 px-6 font-bold text-slate-900 dark:text-white">
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
                                    <td class="py-4 px-6 text-slate-600 dark:text-slate-400">
                                        @if($req->isRejected() && $req->rejection_reason)
                                            <span class="text-rose-600 font-semibold">{{ $req->rejection_reason }}</span>
                                        @else
                                            {{ $req->notes ?: '-' }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                        No stock allocation requests submitted yet.
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
        </div>
    </div>
</x-app-layout>
