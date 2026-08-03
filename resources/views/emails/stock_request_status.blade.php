<x-emails.layout title="Stock Request Status Update">
    <div class="greeting">Hello {{ $stockRequest->client->name }},</div>

    <p>Your stock allocation request <strong>#{{ $stockRequest->request_number }}</strong> has been reviewed by Ceylon AG administration.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Request Code</td>
            <td class="data-val">{{ $stockRequest->request_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Product Name</td>
            <td class="data-val">{{ $stockRequest->product->name }}</td>
        </tr>
        <tr>
            <td class="data-label">Requested Quantity</td>
            <td class="data-val">{{ $stockRequest->requested_quantity }} units</td>
        </tr>
        <tr>
            <td class="data-label">Decision Status</td>
            <td class="data-val" style="color: {{ $stockRequest->isApproved() ? '#059669' : '#e11d48' }};">
                {{ strtoupper($stockRequest->status) }}
            </td>
        </tr>
        @if($stockRequest->isRejected() && $stockRequest->rejection_reason)
            <tr>
                <td class="data-label">Rejection Reason</td>
                <td class="data-val" style="color: #e11d48;">{{ $stockRequest->rejection_reason }}</td>
            </tr>
        @endif
    </table>

    <div class="btn-container">
        <a href="{{ $clientUrl }}" class="btn">View Stock Requests</a>
    </div>
</x-emails.layout>
