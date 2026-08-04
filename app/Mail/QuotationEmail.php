<?php

namespace App\Mail;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Quotation $quotation;
    public string $pdfBinary;

    /**
     * Create a new message instance.
     */
    public function __construct(Quotation $quotation, string $pdfBinary)
    {
        $this->quotation = $quotation;
        $this->pdfBinary = $pdfBinary;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Formal Price Quotation - {$this->quotation->quotation_number} - Ceylon AG",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation',
            with: [
                'quotation' => $this->quotation,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $filename = "Quotation-{$this->quotation->quotation_number}.pdf";

        return [
            Attachment::fromData(fn () => $this->pdfBinary, $filename)
                ->as($filename)
                ->withMime('application/pdf'),
        ];
    }
}
