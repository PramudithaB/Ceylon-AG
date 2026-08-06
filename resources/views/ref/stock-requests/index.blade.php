<x-ref-layout>
    <x-slot name="header">
        Submit & Track Product Stock Requests
    </x-slot>

    <!-- Action Bar Card -->
    <div class="ref-card rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Stock Requests Log</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">Submit product inventory requests to Admin on behalf of assigned clients</p>
        </div>

        <a href="{{ route('ref.stock-requests.create') }}" 
            class="px-5 py-3 bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] text-white font-extrabold text-xs rounded-2xl shadow-lg shadow-emerald-700/20 hover:opacity-95 transition-all flex items-center justify-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Submit New Stock Request</span>
        </a>
    </div>

    <!-- Requests Log Data Table -->
    <div class="ref-card rounded-3xl overflow-hidden">
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

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $stockRequests->links() }}
        </div>
    </div>
</x-ref-layout>
