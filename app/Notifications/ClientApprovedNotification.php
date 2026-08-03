<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $status = 'approved'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->status === 'approved' 
            ? 'Account Approved - Welcome to Ceylon AG Distribution Portal' 
            : 'Client Account Status Update - Ceylon AG';

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.client_approved', [
                'user' => $this->user,
                'status' => $this->status,
                'loginUrl' => route('login'),
            ]);
    }
}
