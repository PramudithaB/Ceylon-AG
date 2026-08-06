<?php

namespace App\Notifications;

use App\Models\StockRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewStockRequestSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public StockRequest $stockRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(StockRequest $stockRequest)
    {
        $this->stockRequest = $stockRequest;
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
        $clientName = $this->stockRequest->client->business_name ?? $this->stockRequest->client->name;
        $productName = $this->stockRequest->product->name ?? 'Product';

        return (new MailMessage)
            ->subject('New Stock Request: ' . $this->stockRequest->request_number)
            ->greeting('Hello Admin,')
            ->line("A new stock request has been submitted for client {$clientName}.")
            ->line("Product: {$productName}")
            ->line("Quantity Requested: " . number_format($this->stockRequest->requested_quantity))
            ->line("Priority: " . ucfirst($this->stockRequest->priority ?? 'medium'))
            ->action('Review Stock Request', route('admin.stock-requests.index'))
            ->line('Please review and process this stock request in the admin portal.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'stock_request_id' => $this->stockRequest->id,
            'request_number' => $this->stockRequest->request_number,
            'client_id' => $this->stockRequest->client_id,
            'title' => 'New Stock Request Submitted',
            'message' => 'Stock request ' . $this->stockRequest->request_number . ' submitted for ' . ($this->stockRequest->client->business_name ?? $this->stockRequest->client->name),
        ];
    }
}
