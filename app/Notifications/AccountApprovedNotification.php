<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Activated - ' . config('app.name', 'Ceylon AG'))
            ->greeting('Hello ' . ($notifiable->first_name ?? $notifiable->name) . '!')
            ->line('Great news! Your business client account registration has been reviewed and approved by an administrator.')
            ->line('You can now log in to access your dashboard and client services.')
            ->action('Log In to Account', route('login'))
            ->line('Thank you for choosing Ceylon AG for your business!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'status' => 'approved',
            'user_id' => $notifiable->id,
        ];
    }
}
