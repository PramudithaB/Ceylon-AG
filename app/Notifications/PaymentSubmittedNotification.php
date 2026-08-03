<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Bank Payment Proof Submitted - ' . $this->payment->payment_number)
            ->view('emails.payment_submitted', [
                'payment' => $this->payment,
                'adminUrl' => route('admin.payments.show', $this->payment->id),
            ]);
    }
}
