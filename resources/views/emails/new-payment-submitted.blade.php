<x-emails.layout title="New Payment Proof Submitted">
    <div class="greeting">Hello Administrator,</div>

    <p>A client partner has submitted bank payment proof for administrative verification and approval.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Payment Reference #</td>
            <td class="data-val">{{ $payment->payment_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Client Business Name</td>
            <td class="data-val">{{ $payment->client->business_name ?? 'N/A' }} ({{ $payment->client->name }})</td>
        </tr>
        <tr>
            <td class="data-label">Payment Amount</td>
            <td class="data-val" style="color: #059669; font-size: 16px;">LKR {{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="data-label">Payment Date</td>
            <td class="data-val">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '' }}</td>
        </tr>
        <tr>
            <td class="data-label">Bank Name</td>
            <td class="data-val">{{ $payment->bank_name }}</td>
        </tr>
        <tr>
            <td class="data-label">Bank Reference / Slip #</td>
            <td class="data-val">{{ $payment->reference_number }}</td>
        </tr>
        @if($payment->remarks)
            <tr>
                <td class="data-label">Client Remarks</td>
                <td class="data-val">{{ $payment->remarks }}</td>
            </tr>
        @endif
    </table>

    <div class="btn-container">
        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn">
            Review & Approve Payment
        </a>
    </div>
</x-emails.layout>
