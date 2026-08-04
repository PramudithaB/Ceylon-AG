<?php

namespace App\Services;

use App\Mail\QuotationEmail;
use App\Models\Quotation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QuotationMailService
{
    protected QuotationPdfService $pdfService;

    public function __construct(QuotationPdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Send email with attached PDF to recipient email address.
     */
    public function sendQuotationEmail(Quotation $quotation, ?string $recipientEmail = null): bool
    {
        $email = $recipientEmail ?? $quotation->email ?? $quotation->client?->email;

        if (!$email) {
            Log::warning("Cannot send quotation #{$quotation->quotation_number}: No recipient email found.");
            return false;
        }

        try {
            $pdfBinary = $this->pdfService->generatePdfBinary($quotation);

            Mail::to($email)->send(new QuotationEmail($quotation, $pdfBinary));

            $quotation->update([
                'status' => Quotation::STATUS_SENT,
                'sent_at' => now(),
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error("Failed sending quotation email #{$quotation->quotation_number} to {$email}: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            return false;
        }
    }
}
