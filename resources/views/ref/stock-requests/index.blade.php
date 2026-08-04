<x-ref-layout>
    <x-slot name="header">
        Submit & Track Product Stock Requests
    </x-slot>

    <!-- Header Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900/90 border border-slate-800 p-5 rounded-2xl shadow-xl">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Stock Requests Log</h2>
            <p class="text-xs text-slate-400 mt-0.5">Submit product stock requests to Admin on behalf of clients</p>
        </div>

        <a href="{{ route('ref.stock-requests.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Submit New Stock Request
        </a>
    </div>

    <!-- Stock Requests Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/70 text-slate-400 uppercase font-semibold border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">Request #</th>
                        <th class="px-5 py-3.5">Client Name</th>
                        <th class="px-5 py-3.5">Requested Product</th>
                        <th class="px-5 py-3.5">Quantity</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Date Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($stockRequests as $req)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4 font-bold text-emerald-400">{{ $req->request_number }}</td>
                            <td class="px-5 py-4 font-medium text-white">{{ $req->client->business_name ?? $req->client->name }}</td>
                            <td class="px-5 py-4 text-slate-200 font-semibold">{{ $req->product->name ?? 'Product' }}</td>
                            <td class="px-5 py-4 font-bold text-white">{{ number_format($req->requested_quantity) }}</td>
                            <td class="px-5 py-4">
                                @if($req->isApproved())
                                    <span class="px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-semibold text-[11px] rounded-full">
                                        Approved
                                    </span>
                                @elseif($req->isPending())
                                    <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-semibold text-[11px] rounded-full">
                                        Pending Admin Review
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 font-semibold text-[11px] rounded-full">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-400">{{ $req->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                                No stock requests submitted yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            {{ $stockRequests->links() }}
        </div>
    </div>
</x-ref-layout>
