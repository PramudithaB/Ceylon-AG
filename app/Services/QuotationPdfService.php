<?php

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationPdfService
{
    /**
     * Generate PDF binary content for given quotation.
     */
    public function generatePdfBinary(Quotation $quotation): string
    {
        $quotation->load(['items', 'client', 'creator']);
        $settings = CompanySetting::getSettings();

        $pdf = Pdf::loadView('admin.quotations.pdf', [
            'quotation' => $quotation,
            'settings' => $settings,
        ])->setPaper('a4', 'portrait');

        return $pdf->output();
    }

    /**
     * Download PDF response directly for browser.
     */
    public function downloadPdfResponse(Quotation $quotation)
    {
        $quotation->load(['items', 'client', 'creator']);
        $settings = CompanySetting::getSettings();

        $filename = "Quotation-{$quotation->quotation_number}.pdf";

        return Pdf::loadView('admin.quotations.pdf', [
            'quotation' => $quotation,
            'settings' => $settings,
        ])->setPaper('a4', 'portrait')->download($filename);
    }
}
