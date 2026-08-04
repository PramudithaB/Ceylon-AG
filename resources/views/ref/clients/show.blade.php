<x-ref-layout>
    <x-slot name="header">
        Client Profile Details
    </x-slot>

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ref.clients.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 font-semibold">
            &larr; Back to Clients Directory
        </a>
    </div>

    <!-- Client Header Card -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <img src="{{ $client->profile_photo_url }}" alt="{{ $client->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-emerald-500/40 shadow-md">
            <div>
                <span class="px-2.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase rounded-full tracking-wider">
                    Client Account
                </span>
                <h2 class="text-2xl font-extrabold text-white mt-1">{{ $client->business_name ?? $client->name }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ $client->name }} &bull; {{ $client->email }} &bull; {{ $client->phone }}</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="flex items-center gap-4 bg-slate-950/60 p-4 rounded-xl border border-slate-800">
            <div>
                <span class="text-[10px] font-semibold text-slate-400 uppercase block">Total Purchases</span>
                <span class="text-sm font-extrabold text-emerald-400">LKR {{ number_format($totalPurchases, 2) }}</span>
            </div>
            <div class="w-px h-8 bg-slate-800"></div>
            <div>
                <span class="text-[10px] font-semibold text-slate-400 uppercase block">Approved Payments</span>
                <span class="text-sm font-extrabold text-cyan-400">LKR {{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Details -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-3">Account Details</h3>
            
            <div class="text-xs space-y-3 text-slate-300">
                <div>
                    <span class="text-slate-500 block text-[11px]">NIC Number</span>
                    <span class="font-semibold text-white">{{ $client->nic ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">Address</span>
                    <span class="font-semibold text-white">{{ $client->address ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">District & Province</span>
                    <span class="font-semibold text-white">{{ $client->district ?? 'N/A' }}, {{ $client->province ?? '' }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px]">Registered Date</span>
                    <span class="font-semibold text-white">{{ $client->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Product Assignments -->
        <div class="lg:col-span-2 bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-3">Assigned Products & Inventory Allocation</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-950/60 text-slate-400 uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-2.5">Product</th>
                            <th class="px-4 py-2.5">Assigned Qty</th>
                            <th class="px-4 py-2.5">Selling Price</th>
                            <th class="px-4 py-2.5">Total Dealer Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($productAssignments as $assignment)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-white">{{ $assignment->product->name ?? 'Product' }}</td>
                                <td class="px-4 py-3 font-bold text-emerald-400">{{ $assignment->quantity }}</td>
                                <td class="px-4 py-3">LKR {{ number_format($assignment->selling_price, 2) }}</td>
                                <td class="px-4 py-3 font-semibold text-slate-200">LKR {{ number_format($assignment->total_dealer_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500">
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
