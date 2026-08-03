<?php

namespace App\Notifications;

use App\Models\StockRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockRequestStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public StockRequest $stockRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = strtoupper($this->stockRequest->status);

        return (new MailMessage)
            ->subject("Stock Request #{$this->stockRequest->request_number} {$statusText} - Ceylon AG")
            ->view('emails.stock_request_status', [
                'stockRequest' => $this->stockRequest,
                'clientUrl' => route('stock-requests.index'),
            ]);
    }
}
