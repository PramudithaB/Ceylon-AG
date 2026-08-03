<x-emails.layout title="New Product Allocation">
    <div class="greeting">Hello {{ $assignment->client->name }},</div>

    <p>A new stock allocation has been dispatched and assigned to your business account from Ceylon AG central warehouse.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Assignment Code</td>
            <td class="data-val">{{ $assignment->assignment_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Product Name</td>
            <td class="data-val">{{ $assignment->product->name }}</td>
        </tr>
        <tr>
            <td class="data-label">Assigned Quantity</td>
            <td class="data-val">{{ $assignment->quantity }} units</td>
        </tr>
        <tr>
            <td class="data-label">Dealer Price</td>
            <td class="data-val">LKR {{ number_format($assignment->dealer_price, 2) }}</td>
        </tr>
        <tr>
            <td class="data-label">Selling Price</td>
            <td class="data-val">LKR {{ number_format($assignment->selling_price, 2) }}</td>
        </tr>
    </table>

    <div class="btn-container">
        <a href="{{ $dashboardUrl }}" class="btn">View Assigned Inventory</a>
    </div>
</x-emails.layout>
