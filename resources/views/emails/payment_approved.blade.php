<x-emails.layout title="Payment Approved">
    <div class="greeting">Hello {{ $payment->client->name }},</div>

    <p>Your bank transfer payment submission <strong>#{{ $payment->payment_number }}</strong> has been verified and <strong style="color: #059669;">APPROVED</strong> by Ceylon AG financial administration.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Payment Ref #</td>
            <td class="data-val">{{ $payment->payment_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Approved Amount</td>
            <td class="data-val">LKR {{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="data-label">Bank Reference</td>
            <td class="data-val">{{ $payment->bank_name }} ({{ $payment->reference_number }})</td>
        </tr>
        <tr>
            <td class="data-label">Payment Date</td>
            <td class="data-val">{{ $payment->payment_date->format('Y-m-d') }}</td>
        </tr>
    </table>

    <div class="btn-container">
        <a href="{{ $voucherUrl }}" class="btn">View Payment Receipt Voucher</a>
    </div>
</x-emails.layout>
