<x-emails.layout title="Payment Proof Submitted">
    <div class="greeting">Hello Administrator,</div>

    <p>Client partner <strong>{{ $payment->client->name }}</strong> ({{ $payment->client->business_name }}) has submitted bank transfer payment proof for administrative verification.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Payment Ref #</td>
            <td class="data-val">{{ $payment->payment_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Payment Amount</td>
            <td class="data-val">LKR {{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="data-label">Bank & Reference</td>
            <td class="data-val">{{ $payment->bank_name }} ({{ $payment->reference_number }})</td>
        </tr>
        <tr>
            <td class="data-label">Payment Date</td>
            <td class="data-val">{{ $payment->payment_date->format('Y-m-d') }}</td>
        </tr>
    </table>

    <div class="btn-container">
        <a href="{{ $adminUrl }}" class="btn">Verify Bank Slip & Approve</a>
    </div>
</x-emails.layout>
