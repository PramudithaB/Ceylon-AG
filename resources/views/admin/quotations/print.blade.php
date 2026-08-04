<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation-{{ $quotation->quotation_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        @media print {
            body {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-900 min-h-screen py-8 print:py-0">

    <!-- Print Control Floating Toolbar -->
    <div class="max-w-4xl mx-auto mb-6 px-4 no-print flex items-center justify-between">
        <a href="{{ route('admin.quotations.show', $quotation->id) }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-1.5">
            &larr; Back to Details
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs rounded-xl shadow-lg flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Quotation
            </button>
            <a href="{{ route('admin.quotations.pdf', $quotation->id) }}" class="px-4 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs rounded-xl shadow-md transition-colors">
                Download PDF
            </a>
        </div>
    </div>

    <!-- Main Printable A4 Container -->
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-2xl rounded-xl border border-slate-200 print-container space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-emerald-700 pb-6">
            <div class="flex items-center gap-4">
                @if($settings->logo_path && file_exists(public_path($settings->logo_path)))
                    <img src="{{ asset($settings->logo_path) }}" alt="Company Logo" class="h-16 w-auto object-contain">
                @else
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-700 to-teal-500 flex items-center justify-center text-white font-black text-xl shadow-md">
                        CAG
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-black text-emerald-950 uppercase tracking-tight">{{ $settings->company_name }}</h1>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        {{ $settings->address }}<br>
                        Phone: {{ $settings->phone }} &bull; Email: {{ $settings->email }}<br>
                        Website: {{ $settings->website }}
                    </p>
                </div>
            </div>

            <div class="text-right">
                <div class="inline-block px-4 py-1 bg-emerald-700 text-white font-black text-xs uppercase tracking-widest rounded mb-2">
                    FORMAL PRICE QUOTATION
                </div>
                <div class="text-xs font-bold text-slate-800 space-y-0.5">
                    <div>Quotation #: <span class="text-emerald-800 font-extrabold text-sm">{{ $quotation->quotation_number }}</span></div>
                    <div>Quotation Date: <span class="font-normal">{{ $quotation->quotation_date ? $quotation->quotation_date->format('d/m/Y') : '' }}</span></div>
                    <div>Valid Until: <span class="font-normal">{{ $quotation->expiry_date ? $quotation->expiry_date->format('d/m/Y') : '' }}</span></div>
                </div>
            </div>
        </div>

        <!-- Customer & Contact Box -->
        <div class="grid grid-cols-2 gap-6 bg-emerald-50/50 p-5 rounded-lg border border-emerald-100 text-xs">
            <div>
                <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[10px] block mb-1">Customer / Client Information</span>
                <div class="font-black text-sm text-slate-900">{{ $quotation->business_name ?? $quotation->customer_name }}</div>
                <div class="text-slate-700 font-semibold">Attn: {{ $quotation->customer_name }}</div>
                @if($quotation->address)<div class="text-slate-600 mt-0.5">{{ $quotation->address }}</div>@endif
                @if($quotation->district)<div class="text-slate-600">District: {{ $quotation->district }}</div>@endif
            </div>

            <div class="text-right space-y-0.5">
                <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[10px] block mb-1">Direct Contact Details</span>
                @if($quotation->phone)<div class="text-slate-800 font-bold">Phone: {{ $quotation->phone }}</div>@endif
                @if($quotation->email)<div class="text-slate-800 font-bold">Email: {{ $quotation->email }}</div>@endif
            </div>
        </div>

        <!-- Product Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-emerald-800 text-white font-extrabold uppercase text-[10px]">
                        <th class="py-2.5 px-3">#</th>
                        <th class="py-2.5 px-3">Product Name</th>
                        <th class="py-2.5 px-3">Description</th>
                        <th class="py-2.5 px-3 text-center">Qty</th>
                        <th class="py-2.5 px-3 text-right">Unit Price (LKR)</th>
                        <th class="py-2.5 px-3 text-right">Discount (LKR)</th>
                        <th class="py-2.5 px-3 text-right">Total Amount (LKR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($quotation->items as $index => $item)
                        <tr>
                            <td class="py-3 px-3 font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-3 font-bold text-slate-900">{{ $item->product_name }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $item->description ?? '-' }}</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-800">{{ $item->quantity }}</td>
                            <td class="py-3 px-3 text-right font-medium text-slate-700">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-3 text-right font-medium text-rose-600">{{ $item->discount > 0 ? number_format($item->discount, 2) : '-' }}</td>
                            <td class="py-3 px-3 text-right font-black text-emerald-950">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Financial Summary & Company Bank Details -->
        <div class="flex justify-between items-start gap-6 pt-2">
            <!-- Bank Details -->
            <div class="w-1/2 bg-slate-50 p-4 rounded-lg border border-slate-200 text-xs space-y-1">
                <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[10px] block mb-1">Company Bank Account Details</span>
                <div class="grid grid-cols-2 gap-x-2 gap-y-0.5 text-slate-700">
                    <div>Bank: <span class="font-bold text-slate-900">{{ $quotation->bank_name ?? $settings->bank_name }}</span></div>
                    <div>Branch: <span class="font-bold text-slate-900">{{ $quotation->bank_branch ?? $settings->branch }}</span></div>
                    <div>Account Name: <span class="font-bold text-slate-900">{{ $quotation->account_name ?? $settings->account_name }}</span></div>
                    <div>Account #: <span class="font-black text-emerald-900">{{ $quotation->account_number ?? $settings->account_number }}</span></div>
                    @if($quotation->swift_code || $settings->swift_code)
                        <div>Swift Code: <span class="font-bold text-slate-900">{{ $quotation->swift_code ?? $settings->swift_code }}</span></div>
                    @endif
                </div>
            </div>

            <!-- Totals -->
            <div class="w-72 space-y-1 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-200 text-slate-700">
                    <span>Subtotal:</span>
                    <span class="font-bold text-slate-900">LKR {{ number_format($quotation->subtotal, 2) }}</span>
                </div>

                @if($quotation->discount_amount > 0)
                    <div class="flex justify-between py-1 border-b border-slate-200 text-slate-700">
                        <span>Overall Discount:</span>
                        <span class="font-bold text-rose-600">- LKR {{ number_format($quotation->discount_amount, 2) }}</span>
                    </div>
                @endif

                @if($quotation->tax_amount > 0)
                    <div class="flex justify-between py-1 border-b border-slate-200 text-slate-700">
                        <span>Tax / Handling:</span>
                        <span class="font-bold text-slate-900">+ LKR {{ number_format($quotation->tax_amount, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between py-2 border-t-2 border-emerald-700 text-sm font-black text-emerald-950">
                    <span>Grand Total:</span>
                    <span>LKR {{ number_format($quotation->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes & Terms -->
        <div class="grid grid-cols-2 gap-6 text-[11px] border-t border-slate-200 pt-4">
            @if($quotation->notes)
                <div>
                    <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[9px] block mb-0.5">Special Instructions</span>
                    <p class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $quotation->notes }}</p>
                </div>
            @endif

            @if($quotation->terms_conditions)
                <div>
                    <span class="font-extrabold text-emerald-900 uppercase tracking-wider text-[9px] block mb-0.5">Terms & Conditions</span>
                    <p class="text-slate-700 whitespace-pre-line leading-relaxed">{{ $quotation->terms_conditions }}</p>
                    @if($quotation->delivery_period)
                        <p class="text-slate-900 font-bold mt-1">Delivery Period: {{ $quotation->delivery_period }}</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Signatures & Authorized Seal -->
        <div class="grid grid-cols-3 gap-6 text-center text-xs border-t border-slate-300 pt-8 mt-6">
            <div>
                <div class="h-10 border-b border-slate-400 mb-1"></div>
                <div class="font-bold text-slate-900">{{ $quotation->prepared_by ?? $quotation->creator?->name }}</div>
                <div class="text-[10px] text-slate-500 uppercase">Prepared By</div>
            </div>

            <div>
                <div class="h-10 border-b border-slate-400 mb-1"></div>
                <div class="font-bold text-slate-900">{{ $quotation->approved_by ?? 'Authorized Manager' }}</div>
                <div class="text-[10px] text-slate-500 uppercase">Approved By</div>
            </div>

            <div class="flex flex-col items-center justify-end">
                <div class="w-16 h-16 border border-dashed border-emerald-600 rounded-full flex items-center justify-center text-[9px] font-bold text-emerald-800 uppercase tracking-widest mb-1">
                    Company Seal
                </div>
                <div class="text-[10px] text-slate-500 uppercase font-semibold">Authorized Signature</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-[10px] text-slate-400 border-t border-slate-100 pt-4">
            Thank you for considering {{ $settings->company_name }}. For inquiries regarding this quotation, please contact {{ $settings->email }} or {{ $settings->phone }}.
        </div>
    </div>
</body>
</html>
