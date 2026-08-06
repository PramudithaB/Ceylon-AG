<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPaymentRecordedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Payment $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $clientName = $this->payment->client->business_name ?? $this->payment->client->name;
        $collectorName = $this->payment->collector->full_name ?? 'Sales Representative';

        return (new MailMessage)
            ->subject('New Payment Recorded: ' . $this->payment->payment_number)
            ->greeting('Hello Admin,')
            ->line("Sales Representative {$collectorName} has recorded a new payment collection for client {$clientName}.")
            ->line("Payment Amount: LKR " . number_format((float) $this->payment->amount, 2))
            ->line("Payment Method: " . ucfirst(str_replace('_', ' ', $this->payment->payment_method)))
            ->line("Reference Number: " . $this->payment->reference_number)
            ->action('Review Payment Verification', route('admin.payments.index'))
            ->line('Please verify or reject this payment in the admin portal.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'payment_number' => $this->payment->payment_number,
            'client_id' => $this->payment->client_id,
            'amount' => (float) $this->payment->amount,
            'title' => 'New Payment Recorded',
            'message' => 'Payment of LKR ' . number_format((float) $this->payment->amount, 2) . ' recorded for ' . ($this->payment->client->business_name ?? $this->payment->client->name) . ' awaiting verification.',
        ];
    }
}
