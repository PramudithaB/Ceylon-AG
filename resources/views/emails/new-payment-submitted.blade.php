<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Payment Proof Submitted</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { border-bottom: 2px solid #059669; padding-bottom: 16px; margin-bottom: 24px; }
        .brand { font-size: 20px; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: 1px; }
        .title { font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 4px; }
        .grid { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .grid td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .label { color: #64748b; font-weight: 600; width: 40%; }
        .val { color: #0f172a; font-weight: 700; }
        .amount { color: #059669; font-size: 18px; }
        .btn { display: inline-block; background-color: #059669; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; margin-top: 24px; }
        .footer { margin-top: 32px; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="brand">Ceylon AG Admin Portal</div>
            <div class="title">New Bank Payment Submitted</div>
        </div>

        <p style="font-size: 14px; color: #334155;">
            A client partner has submitted bank payment proof for administrative verification and approval.
        </p>

        <table class="grid">
            <tr>
                <td class="label">Payment Reference #:</td>
                <td class="val">{{ $payment->payment_number }}</td>
            </tr>
            <tr>
                <td class="label">Client Business Name:</td>
                <td class="val">{{ $payment->client->business_name ?? 'N/A' }} ({{ $payment->client->name }})</td>
            </tr>
            <tr>
                <td class="label">Payment Amount:</td>
                <td class="val amount">LKR {{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Payment Date:</td>
                <td class="val">{{ $payment->payment_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Bank Name:</td>
                <td class="val">{{ $payment->bank_name }}</td>
            </tr>
            <tr>
                <td class="label">Bank Reference / Slip #:</td>
                <td class="val">{{ $payment->reference_number }}</td>
            </tr>
            @if($payment->remarks)
                <tr>
                    <td class="label">Client Remarks:</td>
                    <td class="val">{{ $payment->remarks }}</td>
                </tr>
            @endif
        </table>

        <div style="text-align: center;">
            <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn">
                Review & Approve Payment
            </a>
        </div>

        <div class="footer">
            Ceylon AG Management System — Automated Administrative Dispatch
        </div>
    </div>
</body>
</html>
