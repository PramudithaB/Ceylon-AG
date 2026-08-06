<x-ref-layout>
    <x-slot name="header">
        Client Profile Details
    </x-slot>

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ref.clients.index') }}" class="text-xs font-bold text-gray-500 hover:text-[#1E8E3E] flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Clients Directory</span>
        </a>
    </div>

    <!-- Client Header Card -->
    <div class="ref-card rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <img src="{{ $client->profile_photo_url }}" alt="{{ $client->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl object-cover border-4 border-emerald-100 shadow-md">
            <div>
                <span class="px-3 py-1 bg-emerald-50 text-[#1E8E3E] border border-emerald-200 text-[10px] font-extrabold uppercase rounded-full tracking-wider">
                    Assigned Portfolio Client
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">{{ $client->business_name ?? $client->name }}</h2>
                <p class="text-xs sm:text-sm text-gray-500 font-medium mt-0.5">{{ $client->name }} &bull; {{ $client->email }} &bull; {{ $client->phone }}</p>
            </div>
        </div>

        <!-- Quick Financial Metrics Pill -->
        <div class="flex items-center gap-6 bg-emerald-50/60 p-5 rounded-2xl border border-emerald-100/80 shrink-0">
            <div>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Total Purchases</span>
                <span class="text-base font-extrabold text-[#1E8E3E]">LKR {{ number_format($totalPurchases, 2) }}</span>
            </div>
            <div class="w-px h-8 bg-emerald-200"></div>
            <div>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">Approved Payments</span>
                <span class="text-base font-extrabold text-gray-900">LKR {{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Account Info -->
        <div class="ref-card rounded-3xl p-6 sm:p-8 space-y-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-3">Account Information</h3>
            
            <div class="space-y-4 text-xs font-medium text-gray-700">
                <div>
                    <span class="text-gray-400 block text-[11px] font-semibold">NIC Number</span>
                    <span class="font-extrabold text-gray-900 text-sm mt-0.5 block">{{ $client->nic ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px] font-semibold">Street Address</span>
                    <span class="font-extrabold text-gray-900 text-sm mt-0.5 block">{{ $client->address ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px] font-semibold">District & Province</span>
                    <span class="font-extrabold text-gray-900 text-sm mt-0.5 block">{{ $client->district ?? 'N/A' }}, {{ $client->province ?? '' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px] font-semibold">Registered Date</span>
                    <span class="font-extrabold text-gray-900 text-sm mt-0.5 block">{{ $client->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Product Assignments & Inventory Allocation -->
        <div class="lg:col-span-2 ref-card rounded-3xl p-6 sm:p-8 space-y-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-3">Assigned Products & Inventory Allocation</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium text-gray-700">
                    <thead class="bg-gray-50/80 text-gray-500 uppercase tracking-wider font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3.5">Product</th>
                            <th class="px-5 py-3.5 text-center">Assigned Qty</th>
                            <th class="px-5 py-3.5">Selling Price</th>
                            <th class="px-5 py-3.5">Total Dealer Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($productAssignments as $assignment)
                            <tr class="hover:bg-emerald-50/40 transition-colors">
                                <td class="px-5 py-4 font-extrabold text-gray-900">{{ $assignment->product->name ?? 'Product' }}</td>
                                <td class="px-5 py-4 text-center font-bold text-[#1E8E3E]">{{ $assignment->quantity }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">LKR {{ number_format($assignment->selling_price, 2) }}</td>
                                <td class="px-5 py-4 font-extrabold text-[#1E8E3E]">LKR {{ number_format($assignment->total_dealer_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-400 font-medium">
                                    No products assigned to this client yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-ref-layout>
