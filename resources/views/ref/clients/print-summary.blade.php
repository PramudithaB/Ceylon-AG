<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Summary Report - {{ $client->business_name ?? $client->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11px; }
            .print-card { border: none !important; shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-900 min-h-screen p-4 sm:p-8">
    
    <!-- Floating Action Toolbar -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('ref.dashboard', ['client_id' => $client->id]) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-extrabold text-xs rounded-xl transition-all">
            &larr; Back to Client Workspace
        </a>

        <button onclick="window.print()" class="px-6 py-2.5 bg-[#1E8E3E] hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all">
            Print / Save PDF Report
        </button>
    </div>

    <!-- Printable Container -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-xl border border-gray-200 space-y-8 print-card">
        
        <!-- Document Header -->
        <div class="flex items-center justify-between border-b-2 border-[#1E8E3E] pb-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG Logo" class="h-12 w-auto object-contain">
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight">CEYLON AGRO MARKETING</h1>
                    <p class="text-xs font-bold text-[#1E8E3E]">Enterprise Client Relationship Summary Report</p>
                </div>
            </div>

            <div class="text-right text-xs text-gray-500 font-medium">
                <div>Date Generated: <strong class="text-gray-900">{{ date('F d, Y') }}</strong></div>
                <div>Report Ref: <strong class="text-gray-900">CRM-RPT-{{ $client->id }}-{{ date('Ymd') }}</strong></div>
            </div>
        </div>

        <!-- Client & Rep Metadata -->
        <div class="grid grid-cols-2 gap-6 bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100 text-xs">
            <div>
                <span class="text-[10px] font-extrabold uppercase text-[#1E8E3E] tracking-wider block">Client Details</span>
                <div class="text-base font-extrabold text-gray-900 mt-1">{{ $client->business_name ?? $client->name }}</div>
                <div class="text-gray-600 font-medium mt-0.5">Owner: {{ $client->name }}</div>
                <div class="text-gray-600 font-medium">Phone: {{ $client->phone }}</div>
                <div class="text-gray-600 font-medium">Address: {{ $client->address ?? 'N/A' }}, {{ $client->district }}</div>
            </div>

            <div class="text-right">
                <span class="text-[10px] font-extrabold uppercase text-[#1E8E3E] tracking-wider block">Assigned Representative</span>
                <div class="text-base font-extrabold text-gray-900 mt-1">{{ $refUser->full_name }}</div>
                <div class="text-gray-600 font-medium mt-0.5">Email: {{ $refUser->email }}</div>
                <div class="text-gray-600 font-medium">Phone: {{ $refUser->phone ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div>
            <h2 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider mb-3">1. Financial Settlement Summary</h2>
            <div class="grid grid-cols-4 gap-4 text-xs border border-gray-200 rounded-2xl p-4 bg-gray-50">
                <div>
                    <span class="text-gray-400 font-bold block text-[10px] uppercase">Total Goods Assigned</span>
                    <span class="text-sm font-extrabold text-gray-900">LKR {{ number_format($financial_summary['total_given'], 2) }}</span>
                </div>

                <div>
                    <span class="text-gray-400 font-bold block text-[10px] uppercase">Total Paid</span>
                    <span class="text-sm font-extrabold text-[#1E8E3E]">LKR {{ number_format($financial_summary['total_paid'], 2) }}</span>
                </div>

                <div>
                    <span class="text-gray-400 font-bold block text-[10px] uppercase">Outstanding Due</span>
                    <span class="text-sm font-extrabold text-rose-600">LKR {{ number_format($financial_summary['outstanding_balance'], 2) }}</span>
                </div>

                <div>
                    <span class="text-gray-400 font-bold block text-[10px] uppercase">Payment Progress</span>
                    <span class="text-sm font-extrabold text-gray-900">{{ $financial_summary['payment_progress'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Inventory Breakdown -->
        <div>
            <h2 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider mb-3">2. Product Inventory Balance</h2>
            <table class="w-full text-left text-xs border border-gray-200 rounded-2xl overflow-hidden">
                <thead class="bg-gray-100 text-gray-600 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Product Name</th>
                        <th class="p-3 text-center">Assigned Qty</th>
                        <th class="p-3 text-center">Sold Qty</th>
                        <th class="p-3 text-center">Remaining Qty</th>
                        <th class="p-3 text-right">Dealer Unit Price</th>
                        <th class="p-3 text-right">Total Stock Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @foreach($product_stock as $ps)
                        <tr>
                            <td class="p-3 font-bold text-gray-900">{{ $ps->product->name ?? 'Product' }}</td>
                            <td class="p-3 text-center font-bold">{{ $ps->assigned_qty }}</td>
                            <td class="p-3 text-center">{{ $ps->sold_qty }}</td>
                            <td class="p-3 text-center font-extrabold text-[#1E8E3E]">{{ $ps->remaining_qty }}</td>
                            <td class="p-3 text-right">LKR {{ number_format($ps->dealer_price, 2) }}</td>
                            <td class="p-3 text-right font-bold">LKR {{ number_format($ps->stock_value, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Recent Payment Log -->
        <div>
            <h2 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider mb-3">3. Recorded Payment Log</h2>
            <table class="w-full text-left text-xs border border-gray-200 rounded-2xl overflow-hidden">
                <thead class="bg-gray-100 text-gray-600 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Payment #</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Method</th>
                        <th class="p-3">Reference / Bank</th>
                        <th class="p-3 text-right">Amount (LKR)</th>
                        <th class="p-3">Verification</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @foreach($payment_history->take(10) as $pay)
                        <tr>
                            <td class="p-3 font-bold text-gray-900">{{ $pay->payment_number }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') }}</td>
                            <td class="p-3 font-bold uppercase text-[10px]">{{ str_replace('_', ' ', $pay->payment_method ?? 'Bank') }}</td>
                            <td class="p-3">{{ $pay->bank_name ?? 'N/A' }} ({{ $pay->reference_number }})</td>
                            <td class="p-3 text-right font-extrabold text-[#1E8E3E]">LKR {{ number_format((float)$pay->amount, 2) }}</td>
                            <td class="p-3 font-bold uppercase text-[10px]">{{ $pay->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Signatures -->
        <div class="pt-12 grid grid-cols-2 gap-12 text-center text-xs text-gray-500 font-bold border-t border-gray-200">
            <div>
                <div class="border-b border-gray-400 mb-2 pb-8"></div>
                <span>Sales Representative Signature</span>
            </div>
            <div>
                <div class="border-b border-gray-400 mb-2 pb-8"></div>
                <span>Client Confirmation & Stamp</span>
            </div>
        </div>

    </div>

</body>
</html>
