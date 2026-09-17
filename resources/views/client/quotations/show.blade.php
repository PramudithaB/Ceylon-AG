<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Quotation: ') . $quotation->quotation_number }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Top Toolbar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <a href="{{ route('client.quotations.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
                    &larr; Back to Quotations List
                </a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('client.quotations.print', $quotation->id) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                        Print Quotation
                    </a>
                    <a href="{{ route('client.quotations.pdf', $quotation->id) }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                        Download PDF
                    </a>
                </div>
            </div>

            <!-- Corporate Quotation Document -->
            <div class="bg-white text-slate-900 rounded-2xl shadow-xl p-8 md:p-12 space-y-8 border border-slate-200">
                <!-- Header Section -->
                <div class="flex flex-col md:flex-row justify-between items-start border-b-2 border-emerald-600 pb-6 gap-6">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset($settings->logo_path && file_exists(public_path($settings->logo_path)) ? $settings->logo_path : 'images/logo.png') }}" alt="Ceylon AG Logo" class="h-16 w-auto object-contain">
                        <div>
                            <h1 class="text-xl font-black text-emerald-950 uppercase tracking-tight">Ceylon AG</h1>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $settings->address ?? 'I Jothipala Mawatha, Malabe' }}<br>
                                Phone: {{ $settings->phone ?? '076 538 0483' }} &bull; Email: {{ $settings->email ?? 'info@ceylonagromarketing.lk' }}<br>
                                Website: {{ $settings->website ?? 'https://ceylonagromarketing.lk/' }}
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="inline-block px-4 py-1.5 bg-emerald-600 text-white font-black text-sm uppercase tracking-wider rounded-lg mb-2">
                            Formal Price Quotation
                        </div>
                        <div class="text-xs font-bold text-slate-700 space-y-1">
                            <div>Quotation #: <span class="text-emerald-700 font-black text-sm">{{ $quotation->quotation_number }}</span></div>
                            <div>Date: <span class="text-slate-900">{{ $quotation->quotation_date->format('d/m/Y') }}</span></div>
                            <div>Expiry Date: <span class="text-slate-900">{{ $quotation->expiry_date->format('d/m/Y') }}</span></div>
                        </div>
                    </div>
                </div>

                <!-- Customer Details Card -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-200 text-xs">
                    <div class="space-y-1">
                        <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[11px] block">Issued To</span>
                        <div class="font-black text-sm text-slate-900">{{ $quotation->business_name ?? $quotation->customer_name }}</div>
                        <div class="text-slate-700 font-medium">Attn: {{ $quotation->customer_name }}</div>
                        @if($quotation->address)<div class="text-slate-600">{{ $quotation->address }}</div>@endif
                        @if($quotation->district)<div class="text-slate-600">District: {{ $quotation->district }}</div>@endif
                    </div>

                    <div class="space-y-1 md:text-right">
                        <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[11px] block">Contact Info</span>
                        @if($quotation->phone)<div class="text-slate-700 font-bold">Phone: {{ $quotation->phone }}</div>@endif
                        @if($quotation->email)<div class="text-slate-700 font-bold">Email: {{ $quotation->email }}</div>@endif
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-emerald-800 text-white font-extrabold uppercase text-[11px]">
                                <th class="py-3 px-4 rounded-l-lg">#</th>
                                <th class="py-3 px-4">Item / Product Name</th>
                                <th class="py-3 px-4">Description</th>
                                <th class="py-3 px-4 text-center">Qty</th>
                                <th class="py-3 px-4 text-right">Unit Price (LKR)</th>
                                <th class="py-3 px-4 text-right">Discount (LKR)</th>
                                <th class="py-3 px-4 text-right rounded-r-lg">Total Amount (LKR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($quotation->items as $index => $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3.5 px-4 font-bold text-slate-500">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $item->product_name }}</td>
                                    <td class="py-3.5 px-4 text-slate-600">{{ $item->description ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ $item->quantity }}</td>
                                    <td class="py-3.5 px-4 text-right font-medium text-slate-700">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3.5 px-4 text-right font-medium text-slate-600">{{ number_format($item->discount, 2) }}</td>
                                    <td class="py-3.5 px-4 text-right font-black text-emerald-950">{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Summary & Bank Details -->
                <div class="flex flex-col sm:flex-row justify-between items-start gap-6 pt-4 border-t-2 border-slate-200">
                    <div class="w-full sm:w-1/2 space-y-4">
                        <div class="bg-emerald-50/70 border border-emerald-200 p-4 rounded-xl text-xs space-y-1.5">
                            <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[11px] block">Company Bank Details for Payment</span>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-slate-700 font-medium">
                                <div>Bank: <span class="font-bold text-slate-900">{{ $quotation->bank_name ?? $settings->bank_name }}</span></div>
                                <div>Branch: <span class="font-bold text-slate-900">{{ $quotation->bank_branch ?? $settings->branch }}</span></div>
                                <div>Account Name: <span class="font-bold text-slate-900">{{ $quotation->account_name ?? $settings->account_name }}</span></div>
                                <div>Account #: <span class="font-extrabold text-emerald-800">{{ $quotation->account_number ?? $settings->account_number }}</span></div>
                                @if($quotation->swift_code || $settings->swift_code)
                                    <div>Swift Code: <span class="font-bold text-slate-900">{{ $quotation->swift_code ?? $settings->swift_code }}</span></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="w-full sm:w-80 space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-slate-200 text-slate-700">
                            <span class="font-semibold">Subtotal:</span>
                            <span class="font-bold text-slate-900">LKR {{ number_format($quotation->subtotal, 2) }}</span>
                        </div>

                        @if($quotation->discount_amount > 0)
                            <div class="flex justify-between py-1 border-b border-slate-200 text-slate-700">
                                <span class="font-semibold">Overall Discount:</span>
                                <span class="font-bold text-rose-600">- LKR {{ number_format($quotation->discount_amount, 2) }}</span>
                            </div>
                        @endif

                        @if($quotation->tax_amount > 0)
                            <div class="flex justify-between py-1 border-b border-slate-200 text-slate-700">
                                <span class="font-semibold">Tax / Handling:</span>
                                <span class="font-bold text-slate-900">+ LKR {{ number_format($quotation->tax_amount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between py-2 border-t-2 border-emerald-600 text-sm font-black text-emerald-950">
                            <span>Grand Total:</span>
                            <span>LKR {{ number_format($quotation->grand_total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs border-t border-slate-200 pt-6">
                    @if($quotation->notes)
                        <div class="space-y-1">
                            <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[11px] block">Special Instructions</span>
                            <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $quotation->notes }}</p>
                        </div>
                    @endif

                    @if($quotation->terms_conditions)
                        <div class="space-y-1">
                            <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[11px] block">Terms & Conditions</span>
                            <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $quotation->terms_conditions }}</p>
                            @if($quotation->delivery_period)
                                <p class="text-slate-800 font-bold mt-2">Delivery Period: {{ $quotation->delivery_period }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Signatures Section -->
                <div class="grid grid-cols-3 gap-6 text-center text-xs border-t-2 border-slate-200 pt-10 mt-8">
                    <div class="flex flex-col justify-end">
                        <div class="h-14"></div>
                        <div class="border-b border-slate-300 mb-2"></div>
                        <div class="text-[11px] font-bold text-slate-900 uppercase">Prepared By</div>
                    </div>

                    <div class="flex flex-col justify-end">
                        <div class="h-14"></div>
                        <div class="border-b border-slate-300 mb-2"></div>
                        <div class="text-[11px] font-bold text-slate-900 uppercase">Approved By</div>
                        <div class="text-[10px] font-bold text-slate-600">Managing Director</div>
                    </div>

                    <div class="flex flex-col items-center justify-end">
                        <div class="w-20 h-20 border-2 border-dashed border-emerald-500/40 rounded-full flex items-center justify-center text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-2">
                            Company Seal
                        </div>
                        <div class="w-full border-b border-slate-300 mb-2"></div>
                        <div class="text-[11px] font-bold text-slate-900 uppercase">Authorized Signature</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
