<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        @page {
            margin: 10mm 10mm 10mm 10mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }
        .company-title {
            font-size: 15px;
            font-weight: bold;
            color: #064e3b;
            text-transform: uppercase;
        }
        .badge {
            background-color: #047857;
            color: #ffffff;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            display: inline-block;
            border-radius: 4px;
        }
        .customer-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 10px;
            border-radius: 6px;
            margin-top: 12px;
            margin-bottom: 12px;
        }
        .items-table {
            margin-top: 12px;
            margin-bottom: 12px;
        }
        .items-table th {
            background-color: #065f46;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 6px 6px;
            text-align: left;
        }
        .items-table td {
            padding: 6px 6px;
            border-bottom: 1px solid #e2e8f0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table td {
            padding: 3px 6px;
        }
        .grand-total {
            font-size: 12px;
            font-weight: bold;
            color: #064e3b;
            border-top: 2px solid #047857;
            padding-top: 4px;
        }
        .bank-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 8px;
            border-radius: 4px;
            font-size: 9px;
        }
        .signature-table td {
            text-align: center;
            padding-top: 25px;
        }
        .signature-line {
            border-top: 1px solid #94a3b8;
            margin: 0 15px 4px 15px;
        }
        .seal-box {
            border: 1px dashed #047857;
            color: #047857;
            font-weight: bold;
            font-size: 8px;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            line-height: 45px;
            margin: 0 auto 4px auto;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table" style="border-bottom: 2px solid #047857; padding-bottom: 8px;">
        <tr>
            <td style="width: 60%;">
                <div class="company-title">{{ $settings->company_name }}</div>
                <div style="font-size: 9px; color: #475569; margin-top: 3px;">
                    {{ $settings->address }}<br>
                    Phone: {{ $settings->phone }} | Email: {{ $settings->email }}<br>
                    Website: {{ $settings->website }}
                </div>
            </td>
            <td style="width: 40%;" class="text-right">
                <div class="badge">Formal Price Quotation</div>
                <div style="font-size: 10px; font-weight: bold; margin-top: 6px; color: #334155;">
                    Quotation #: <span style="color: #047857; font-size: 11px;">{{ $quotation->quotation_number }}</span><br>
                    Date: {{ $quotation->quotation_date ? $quotation->quotation_date->format('d/m/Y') : '' }}<br>
                    Expiry Date: {{ $quotation->expiry_date ? $quotation->expiry_date->format('d/m/Y') : '' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Customer Box -->
    <div class="customer-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    <div style="font-size: 9px; font-weight: bold; color: #065f46; text-transform: uppercase;">Customer Details</div>
                    <div style="font-size: 11px; font-weight: bold; color: #0f172a; margin-top: 2px;">{{ $quotation->business_name ?? $quotation->customer_name }}</div>
                    <div style="color: #334155;">Attn: {{ $quotation->customer_name }}</div>
                    @if($quotation->address)<div style="color: #64748b;">{{ $quotation->address }}</div>@endif
                    @if($quotation->district)<div style="color: #64748b;">District: {{ $quotation->district }}</div>@endif
                </td>
                <td style="width: 40%;" class="text-right">
                    <div style="font-size: 9px; font-weight: bold; color: #065f46; text-transform: uppercase;">Contact Information</div>
                    @if($quotation->phone)<div style="color: #334155; font-weight: bold; margin-top: 2px;">Phone: {{ $quotation->phone }}</div>@endif
                    @if($quotation->email)<div style="color: #334155; font-weight: bold;">Email: {{ $quotation->email }}</div>@endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 28%;">Product</th>
                <th style="width: 24%;">Description</th>
                <th style="width: 8%;" class="text-center">Qty</th>
                <th style="width: 14%;" class="text-right">Unit Price (LKR)</th>
                <th style="width: 10%;" class="text-right">Discount</th>
                <th style="width: 12%;" class="text-right">Total (LKR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-weight: bold; color: #0f172a;">{{ $item->product_name }}</td>
                    <td style="color: #475569;">{{ $item->description ?? '-' }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right" style="color: #dc2626;">{{ $item->discount > 0 ? number_format($item->discount, 2) : '-' }}</td>
                    <td class="text-right" style="font-weight: bold; color: #064e3b;">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Financial Summary & Bank Info -->
    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <div class="bank-box">
                    <div style="font-weight: bold; color: #065f46; margin-bottom: 4px; text-transform: uppercase; font-size: 8px;">Company Bank Details</div>
                    <div>Bank: <strong>{{ $quotation->bank_name ?? $settings->bank_name }}</strong></div>
                    <div>Branch: <strong>{{ $quotation->bank_branch ?? $settings->branch }}</strong></div>
                    <div>Account Name: <strong>{{ $quotation->account_name ?? $settings->account_name }}</strong></div>
                    <div>Account #: <strong style="color: #047857;">{{ $quotation->account_number ?? $settings->account_number }}</strong></div>
                    @if($quotation->swift_code || $settings->swift_code)
                        <div>Swift Code: <strong>{{ $quotation->swift_code ?? $settings->swift_code }}</strong></div>
                    @endif
                </div>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <table class="totals-table" style="width: 100%;">
                    <tr>
                        <td style="color: #475569;">Subtotal:</td>
                        <td class="text-right" style="font-weight: bold;">LKR {{ number_format($quotation->subtotal, 2) }}</td>
                    </tr>
                    @if($quotation->discount_amount > 0)
                        <tr>
                            <td style="color: #475569;">Overall Discount:</td>
                            <td class="text-right" style="color: #dc2626; font-weight: bold;">- LKR {{ number_format($quotation->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    @if($quotation->tax_amount > 0)
                        <tr>
                            <td style="color: #475569;">Tax / Handling:</td>
                            <td class="text-right" style="font-weight: bold;">+ LKR {{ number_format($quotation->tax_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="grand-total">Grand Total:</td>
                        <td class="text-right grand-total">LKR {{ number_format($quotation->grand_total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Terms & Instructions -->
    <table style="width: 100%; margin-top: 12px; font-size: 8.5px; color: #475569;">
        <tr>
            @if($quotation->notes)
                <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                    <strong style="color: #065f46;">Special Instructions:</strong><br>
                    {!! nl2br(e($quotation->notes)) !!}
                </td>
            @endif
            @if($quotation->terms_conditions)
                <td style="width: 50%; vertical-align: top;">
                    <strong style="color: #065f46;">Terms & Conditions:</strong><br>
                    {!! nl2br(e($quotation->terms_conditions)) !!}
                    @if($quotation->delivery_period)
                        <br><strong>Delivery Period:</strong> {{ $quotation->delivery_period }}
                    @endif
                </td>
            @endif
        </tr>
    </table>

    <!-- Signatures -->
    <table class="signature-table" style="width: 100%; margin-top: 20px;">
        <tr>
            <td style="width: 33%;">
                <div class="signature-line"></div>
                <strong>{{ $quotation->prepared_by ?? $quotation->creator?->name }}</strong><br>
                <span style="font-size: 8px; color: #64748b;">Prepared By</span>
            </td>
            <td style="width: 33%;">
                <div class="signature-line"></div>
                <strong>{{ $quotation->approved_by ?? 'Authorized Manager' }}</strong><br>
                <span style="font-size: 8px; color: #64748b;">Approved By</span>
            </td>
            <td style="width: 34%;">
                <div class="seal-box">SEAL</div>
                <span style="font-size: 8px; color: #64748b;">Authorized Signature</span>
            </td>
        </tr>
    </table>

</body>
</html>
