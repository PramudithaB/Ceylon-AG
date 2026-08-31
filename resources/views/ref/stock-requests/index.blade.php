<x-ref-layout>
    <x-slot name="header">
        Stock Requests & Tracking
    </x-slot>

    <div class="space-y-4">
        <!-- Action Bar Card -->
        <div class="ref-card rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight">Stock Requests Log</h2>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Submit and monitor product inventory requests for clients</p>
            </div>

            <a href="{{ route('ref.stock-requests.create') }}" 
                class="touch-btn px-5 py-2.5 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-md shadow-emerald-700/20 hover:opacity-95 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Request Stock</span>
            </a>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('ref.stock-requests.index') }}" class="ref-card rounded-2xl p-3.5 bg-white flex flex-wrap gap-2.5 items-center">
            <select name="status" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-900 text-white font-extrabold text-xs rounded-xl">Filter</button>
            @if(request('status'))
                <a href="{{ route('ref.stock-requests.index') }}" class="text-xs font-bold text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <!-- MOBILE CARDS VIEW (Phones < 640px) -->
        <div class="sm:hidden space-y-2.5">
            @forelse($stockRequests as $req)
                <div class="ref-card rounded-2xl p-4 space-y-2.5 bg-white">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-[#1E8E3E]">#{{ $req->request_number }}</span>
                        @if($req->isApproved())
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-100 text-[#1E8E3E]">
                                Approved
                            </span>
                        @elseif($req->isPending())
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-100 text-amber-800">
                                Pending Review
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-rose-100 text-rose-700" title="{{ $req->rejection_reason }}">
                                Rejected
                            </span>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-gray-900">{{ $req->client->business_name ?? $req->client->name }}</h3>
                        <p class="text-[11px] text-gray-600 font-medium mt-0.5">
                            {{ $req->product->name ?? 'Product' }} &bull; <strong class="text-gray-900">{{ $req->requested_quantity }} units</strong>
                        </p>
                    </div>

                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-400 font-semibold">
                        <span>Submitted: {{ $req->created_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            @empty
                <div class="ref-card rounded-2xl p-8 text-center text-xs text-gray-400 font-medium">
                    No stock requests submitted yet.
                </div>
            @endforelse
        </div>

        <!-- DESKTOP TABLE VIEW (Screens >= 640px) -->
        <div class="hidden sm:block ref-card rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium text-gray-700">
                    <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Request #</th>
                            <th class="px-6 py-4">Client Business</th>
                            <th class="px-6 py-4">Requested Product</th>
                            <th class="px-6 py-4 text-center">Quantity</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($stockRequests as $req)
                            <tr class="hover:bg-emerald-50/40 transition-colors">
                                <td class="px-6 py-4 font-bold text-[#1E8E3E]">{{ $req->request_number }}</td>
                                <td class="px-6 py-4 font-extrabold text-gray-900">{{ $req->client->business_name ?? $req->client->name }}</td>
                                <td class="px-6 py-4 text-gray-900 font-bold">{{ $req->product->name ?? 'Product' }}</td>
                                <td class="px-6 py-4 text-center font-extrabold text-[#1E8E3E] text-sm">{{ number_format($req->requested_quantity) }}</td>
                                <td class="px-6 py-4">
                                    @if($req->isApproved())
                                        <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-[#1E8E3E] font-extrabold text-[11px] rounded-full">
                                            Approved
                                        </span>
                                    @elseif($req->isPending())
                                        <span class="px-3 py-1 bg-amber-50 border border-amber-200 text-amber-700 font-extrabold text-[11px] rounded-full">
                                            Pending Admin Review
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-rose-50 border border-rose-200 text-rose-600 font-extrabold text-[11px] rounded-full">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 font-medium">{{ $req->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">
                                    No stock requests submitted yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-2">
            {{ $stockRequests->links() }}
        </div>
    </div>
</x-ref-layout>
