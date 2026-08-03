<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPaymentSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Payment Submitted [#{$this->payment->payment_number}] - {$this->payment->client->business_name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-payment-submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
