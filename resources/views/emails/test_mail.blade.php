<x-emails.layout title="Hostinger SMTP Verification Test">
    <div class="greeting">Hello,</div>

    <p>This is an automated test email dispatched from <strong>Ceylon AG</strong> to verify hostinger SMTP server configuration.</p>

    <table class="data-table">
        <tr>
            <td class="data-label">SMTP Host</td>
            <td class="data-val">{{ config('mail.mailers.smtp.host') }}</td>
        </tr>
        <tr>
            <td class="data-label">SMTP Port</td>
            <td class="data-val">{{ config('mail.mailers.smtp.port') }} ({{ config('mail.mailers.smtp.encryption') }})</td>
        </tr>
        <tr>
            <td class="data-label">Sender Address</td>
            <td class="data-val">{{ config('mail.from.address') }}</td>
        </tr>
        <tr>
            <td class="data-label">Target Recipient</td>
            <td class="data-val">{{ $recipientEmail }}</td>
        </tr>
        <tr>
            <td class="data-label">Timestamp</td>
            <td class="data-val">{{ $sentAt }}</td>
        </tr>
        <tr>
            <td class="data-label">Status</td>
            <td class="data-val" style="color: #059669; font-weight: 800;">✓ SMTP Connected & Verified</td>
        </tr>
    </table>

    <p style="font-size: 13px; color: #64748b; margin-top: 20px;">If you received this message, your Hostinger SMTP setup is operational and production-ready.</p>
</x-emails.layout>
