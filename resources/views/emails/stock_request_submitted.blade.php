<x-emails.layout title="New Stock Request Submitted">
    <div class="greeting">Hello Administrator,</div>

    <p>Client partner <strong>{{ $stockRequest->client->name }}</strong> ({{ $stockRequest->client->business_name }}) has submitted a new stock allocation request.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Request Code</td>
            <td class="data-val">{{ $stockRequest->request_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Requested Product</td>
            <td class="data-val">{{ $stockRequest->product->name }}</td>
        </tr>
        <tr>
            <td class="data-label">Requested Quantity</td>
            <td class="data-val">{{ $stockRequest->requested_quantity }} units</td>
        </tr>
        <tr>
            <td class="data-label">Client Notes</td>
            <td class="data-val">{{ $stockRequest->notes ?: 'None' }}</td>
        </tr>
    </table>

    <div class="btn-container">
        <a href="{{ $adminUrl }}" class="btn">Review Stock Request</a>
    </div>
</x-emails.layout>
