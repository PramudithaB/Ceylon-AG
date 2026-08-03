<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment
    ) {}

    public function envelope(): Envelope
    {
        $statusStr = ucfirst($this->payment->status);
        return new Envelope(
            subject: "Payment Status Update: {$statusStr} [#{$this->payment->payment_number}] - Ceylon AG",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-status-updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
