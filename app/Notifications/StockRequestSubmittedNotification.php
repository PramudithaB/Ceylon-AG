<?php

namespace App\Notifications;

use App\Models\StockRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockRequestSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public StockRequest $stockRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Stock Request Submitted - ' . $this->stockRequest->request_number)
            ->view('emails.stock_request_submitted', [
                'stockRequest' => $this->stockRequest,
                'adminUrl' => route('admin.stock-requests.index'),
            ]);
    }
}
