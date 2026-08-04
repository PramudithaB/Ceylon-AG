<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientEmail
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hostinger SMTP Verification Test - Ceylon AG',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test_mail',
            with: [
                'recipientEmail' => $this->recipientEmail,
                'sentAt' => now()->format('Y-m-d H:i:s T'),
            ]
        );
    }
}
