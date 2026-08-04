<x-emails.layout title="Price Quotation - {{ $quotation->quotation_number }}">
    <div class="greeting">Dear {{ $quotation->customer_name }},</div>

    <p>Thank you for choosing Ceylon AG. Please find attached our official price quotation <strong>#{{ $quotation->quotation_number }}</strong> for your review.</p>

    <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; margin: 20px 0;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <tr>
                <td style="color: #475569; padding: 4px 0;">Quotation Number:</td>
                <td style="font-weight: bold; text-align: right; color: #0f172a;">{{ $quotation->quotation_number }}</td>
            </tr>
            <tr>
                <td style="color: #475569; padding: 4px 0;">Issue Date:</td>
                <td style="font-weight: bold; text-align: right; color: #0f172a;">{{ $quotation->quotation_date ? $quotation->quotation_date->format('F d, Y') : '' }}</td>
            </tr>
            <tr>
                <td style="color: #475569; padding: 4px 0;">Valid Until:</td>
                <td style="font-weight: bold; text-align: right; color: #0f172a;">{{ $quotation->expiry_date ? $quotation->expiry_date->format('F d, Y') : '' }}</td>
            </tr>
            <tr style="border-top: 1px dashed #a7f3d0;">
                <td style="font-weight: bold; padding-top: 10px; color: #065f46; font-size: 15px;">Grand Total:</td>
                <td style="text-align: right; padding-top: 10px; font-weight: 900; color: #065f46; font-size: 16px;">LKR {{ number_format($quotation->grand_total, 2) }}</td>
            </tr>
        </table>
    </div>

    <p>The complete document with itemized product descriptions, bank account details, and payment terms is attached as a PDF file to this email.</p>

    <p style="font-size: 13px; color: #64748b; margin-top: 20px;">If you have any questions or require modifications to this quotation, please reply to this email or contact support at <a href="mailto:{{ config('mail.from.address', 'info@ceylonagromarketing.lk') }}" style="color: #059669; font-weight: bold;">{{ config('mail.from.address', 'info@ceylonagromarketing.lk') }}</a>.</p>

    <p style="margin-top: 24px; font-size: 13px;">Sincerely,<br>
    <strong style="color: #0f172a;">Sales & Estimations Team</strong><br>
    Ceylon AG (Pvt) Ltd</p>
</x-emails.layout>
