<x-emails.layout title="Payment Rejected">
    <div class="greeting">Hello {{ $payment->client->name }},</div>

    <p>Your bank transfer payment submission <strong>#{{ $payment->payment_number }}</strong> could not be verified and has been <strong style="color: #e11d48;">REJECTED</strong>.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Payment Ref #</td>
            <td class="data-val">{{ $payment->payment_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Submitted Amount</td>
            <td class="data-val">LKR {{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="data-label">Rejection Reason</td>
            <td class="data-val" style="color: #e11d48;">{{ $payment->rejection_reason }}</td>
        </tr>
    </table>

    <p>Please re-check your payment slip details and submit a new payment proof or contact finance administration.</p>

    <div class="btn-container">
        <a href="{{ route('payments.create') }}" class="btn">Submit New Payment Proof</a>
    </div>
</x-emails.layout>
