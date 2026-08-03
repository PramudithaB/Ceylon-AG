<?php

namespace App\Notifications;

use App\Models\ProductAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ProductAssignment $assignment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Product Inventory Allocation Assigned - Ceylon AG')
            ->view('emails.product_assigned', [
                'assignment' => $this->assignment,
                'dashboardUrl' => route('dashboard'),
            ]);
    }
}
