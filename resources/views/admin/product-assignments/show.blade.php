<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.product-assignments.index') }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Assignment Voucher #{{ $assignment->assignment_number }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Stock allocation record and client dispatch receipt</p>
                </div>
            </div>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 transition-all">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Voucher
            </button>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-8 shadow-sm space-y-8">
            <!-- Header Voucher Info -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 dark:border-slate-800 pb-6 gap-4">
                <div>
                    <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Ceylon AG Allocation Voucher</div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $assignment->assignment_number }}</h2>
                </div>
                <div class="text-right text-xs text-slate-500">
                    <div>Date Assigned: <span class="font-bold text-slate-800 dark:text-slate-200">{{ $assignment->assigned_at->format('F d, Y \a\t h:i A') }}</span></div>
                    <div>Assigned By: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $assignment->assignedBy->name ?? 'System Admin' }}</span></div>
                </div>
            </div>

            <!-- Client & Product Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Client Box -->
                <div class="bg-slate-50 dark:bg-slate-950/60 rounded-2xl p-5 border border-slate-100 dark:border-slate-800 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Client Details</h3>
                    <div>
                        <div class="text-base font-bold text-slate-900 dark:text-white">{{ $assignment->client->name }}</div>
                        <div class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">{{ $assignment->client->business_name }}</div>
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 space-y-1">
                        <div>NIC: {{ $assignment->client->nic }}</div>
                        <div>Phone: {{ $assignment->client->phone }}</div>
                        <div>Email: {{ $assignment->client->email }}</div>
                        <div>Address: {{ $assignment->client->address }}, {{ $assignment->client->district }}, {{ $assignment->client->province }}</div>
                    </div>
                </div>

                <!-- Product Box -->
                <div class="bg-slate-50 dark:bg-slate-950/60 rounded-2xl p-5 border border-slate-100 dark:border-slate-800 space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Allocated Product</h3>
                    <div class="flex items-center space-x-3">
                        <img class="w-12 h-12 rounded-xl object-cover border border-slate-200 dark:border-slate-800" src="{{ $assignment->product->image_url }}" alt="{{ $assignment->product->name }}">
                        <div>
                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $assignment->product->name }}</div>
                            <div class="text-xs font-mono text-slate-400">SKU: {{ $assignment->product->sku }}</div>
                            <div class="text-[10px] text-slate-500">Category: {{ $assignment->product->category->name ?? 'General' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Table -->
            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Description</th>
                            <th class="py-3 px-6 text-center">Allocated Qty</th>
                            <th class="py-3 px-6 text-right">Dealer Price</th>
                            <th class="py-3 px-6 text-right">Retail Price</th>
                            <th class="py-3 px-6 text-right">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr>
                            <td class="py-4 px-6 font-bold text-slate-900 dark:text-white">
                                {{ $assignment->product->name }}
                            </td>
                            <td class="py-4 px-6 text-center font-bold">
                                {{ $assignment->quantity }} units
                            </td>
                            <td class="py-4 px-6 text-right font-semibold">
                                LKR {{ number_format($assignment->dealer_price, 2) }}
                            </td>
                            <td class="py-4 px-6 text-right font-semibold">
                                LKR {{ number_format($assignment->selling_price, 2) }}
                            </td>
                            <td class="py-4 px-6 text-right font-black text-emerald-600 dark:text-emerald-400 text-sm">
                                LKR {{ number_format($assignment->total_dealer_amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($assignment->notes)
                <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl text-xs text-amber-800 dark:text-amber-300">
                    <span class="font-bold">Dispatch & Admin Notes:</span> {{ $assignment->notes }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
