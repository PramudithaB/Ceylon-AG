<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Product Assignment Notification</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; }
        .header { text-align: center; border-b: 2px solid #10b981; padding-bottom: 20px; margin-bottom: 24px; }
        .brand { font-size: 24px; font-weight: 800; color: #059669; letter-spacing: -0.5px; }
        .title { font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 8px; }
        .details-table { width: 100%; border-collapse: collapse; margin: 20px 0; background: #f8fafc; border-radius: 12px; overflow: hidden; }
        .details-table th, .details-table td { padding: 12px 16px; text-align: left; font-size: 14px; }
        .details-table th { background: #e2e8f0; font-size: 12px; text-transform: uppercase; color: #475569; }
        .total-box { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px; text-align: center; margin-top: 20px; }
        .total-amount { font-size: 22px; font-weight: 800; color: #047857; }
        .footer { margin-top: 32px; text-align: center; font-size: 12px; color: #94a3b8; }
        .btn { display: inline-block; background-color: #059669; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">Ceylon AG</div>
            <div class="title">Product Stock Allocated</div>
        </div>

        <p>Dear <strong>{{ $assignment->client->name }}</strong> ({{ $assignment->client->business_name }}),</p>

        <p>We are pleased to inform you that a new product inventory allocation has been assigned to your account by our administration team.</p>

        <table class="details-table">
            <tr>
                <th>Assignment Reference</th>
                <td><strong>{{ $assignment->assignment_number }}</strong></td>
            </tr>
            <tr>
                <th>Product Name</th>
                <td><strong>{{ $assignment->product->name }}</strong> (SKU: {{ $assignment->product->sku }})</td>
            </tr>
            <tr>
                <th>Allocated Quantity</th>
                <td><strong>{{ $assignment->quantity }} units</strong></td>
            </tr>
            <tr>
                <th>Dealer Price (per unit)</th>
                <td>LKR {{ number_format($assignment->dealer_price, 2) }}</td>
            </tr>
            <tr>
                <th>Suggested Retail Price</th>
                <td>LKR {{ number_format($assignment->selling_price, 2) }}</td>
            </tr>
            <tr>
                <th>Allocation Date</th>
                <td>{{ $assignment->assigned_at->format('F d, Y \a\t h:i A') }}</td>
            </tr>
        </table>

        <div class="total-box">
            <div style="font-size: 12px; color: #065f46; font-weight: 600;">TOTAL ALLOCATION VALUE</div>
            <div class="total-amount">LKR {{ number_format($assignment->total_dealer_amount, 2) }}</div>
        </div>

        @if($assignment->notes)
            <div style="margin-top: 20px; padding: 12px; background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 8px; font-size: 13px; color: #92400e;">
                <strong>Admin Notes:</strong> {{ $assignment->notes }}
            </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('dashboard') }}" class="btn">View Assigned Products in Dashboard</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Ceylon AG. All rights reserved.<br>
            If you have questions regarding this allocation, please contact your account manager.
        </div>
    </div>
</body>
</html>
