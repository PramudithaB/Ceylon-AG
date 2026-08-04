<x-emails.layout title="Product Inventory Allocated">
    <div class="greeting">Dear {{ $assignment->client->name }},</div>

    <p>We are pleased to inform you that a new product inventory allocation has been assigned to your account by our administration team.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Assignment Code</td>
            <td class="data-val">{{ $assignment->assignment_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Allocated Product</td>
            <td class="data-val">{{ $assignment->product->name }} (SKU: {{ $assignment->product->sku }})</td>
        </tr>
        <tr>
            <td class="data-label">Quantity Allocated</td>
            <td class="data-val">{{ $assignment->quantity }} units</td>
        </tr>
        <tr>
            <td class="data-label">Dealer Price (unit)</td>
            <td class="data-val">LKR {{ number_format($assignment->dealer_price, 2) }}</td>
        </tr>
        <tr>
            <td class="data-label">Suggested Retail Price</td>
            <td class="data-val">LKR {{ number_format($assignment->selling_price, 2) }}</td>
        </tr>
        <tr>
            <td class="data-label">Allocation Date</td>
            <td class="data-val">{{ $assignment->assigned_at ? $assignment->assigned_at->format('F d, Y \a\t h:i A') : '' }}</td>
        </tr>
    </table>

    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px; text-align: center; margin: 20px 0;">
        <div style="font-size: 11px; color: #065f46; font-weight: 700; text-transform: uppercase;">Total Allocation Value</div>
        <div style="font-size: 20px; font-weight: 900; color: #047857; margin-top: 4px;">LKR {{ number_format($assignment->total_dealer_amount, 2) }}</div>
    </div>

    @if($assignment->notes)
        <div style="padding: 12px; background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 8px; font-size: 13px; color: #92400e; margin-bottom: 20px;">
            <strong>Admin Notes:</strong> {{ $assignment->notes }}
        </div>
    @endif

    <div class="btn-container">
        <a href="{{ route('dashboard') }}" class="btn">View Assigned Products</a>
    </div>
</x-emails.layout>
