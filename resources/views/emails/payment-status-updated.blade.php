<x-emails.layout title="Payment Status Update">
    <div class="greeting">Dear {{ $payment->client->name }},</div>

    <p>Your bank payment submission has been reviewed by the Ceylon AG management team.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">Payment Reference #</td>
            <td class="data-val">{{ $payment->payment_number }}</td>
        </tr>
        <tr>
            <td class="data-label">Review Status</td>
            <td class="data-val">
                @if($payment->isApproved())
                    <span style="color: #059669; font-weight: 800;">✓ APPROVED</span>
                @else
                    <span style="color: #e11d48; font-weight: 800;">✗ REJECTED</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="data-label">Payment Amount</td>
            <td class="data-val">LKR {{ number_format($payment->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="data-label">Bank & Reference</td>
            <td class="data-val">{{ $payment->bank_name }} (Ref: {{ $payment->reference_number }})</td>
        </tr>
        @if($payment->isRejected() && $payment->rejection_reason)
            <tr>
                <td class="data-label" style="color: #e11d48;">Rejection Reason</td>
                <td class="data-val" style="color: #e11d48;">{{ $payment->rejection_reason }}</td>
            </tr>
        @endif
    </table>

    <div class="btn-container">
        <a href="{{ route('payments.show', $payment->id) }}" class="btn">
            View Payment Details
        </a>
    </div>
</x-emails.layout>
