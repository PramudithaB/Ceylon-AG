<?php

namespace App\Services;

use App\Mail\NewPaymentSubmittedMail;
use App\Mail\PaymentStatusUpdatedMail;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository
    ) {}

    public function getClientPayments(User $client, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->paymentRepository->getForClientPaginated($client, $filters, $perPage);
    }

    public function getAllPayments(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->paymentRepository->getAllPaginated($filters, $perPage);
    }

    public function getPaymentById(int $id): Payment
    {
        return $this->paymentRepository->findById($id);
    }

    /**
     * Store new payment submission with screenshot file upload and notify admin.
     */
    public function submitPayment(array $data, UploadedFile $screenshot, User $client): Payment
    {
        return DB::transaction(function () use ($data, $screenshot, $client) {
            // Upload screenshot to storage/app/public/payments
            $path = $screenshot->store('payments', 'public');

            $payment = $this->paymentRepository->create([
                'payment_number' => Payment::generatePaymentNumber(),
                'client_id' => $client->id,
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'],
                'bank_name' => $data['bank_name'],
                'reference_number' => $data['reference_number'],
                'payment_screenshot' => $path,
                'remarks' => $data['remarks'] ?? null,
                'status' => Payment::STATUS_PENDING,
            ]);

            // Notify System Admins via Notification
            try {
                $admins = User::where('role', User::ROLE_ADMIN)->get();
                if ($admins->isEmpty()) {
                    $admins = User::role(['Super Admin', 'Admin'])->get();
                }
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PaymentSubmittedNotification($payment));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Failed sending payment submitted notification for payment #{$payment->payment_number}: " . $e->getMessage(), ['exception' => $e]);
            }

            return $payment;
        });
    }

    /**
     * Approve payment submission and notify client.
     */
    public function approvePayment(Payment $payment, User $reviewer): Payment
    {
        return DB::transaction(function () use ($payment, $reviewer) {
            $updated = $this->paymentRepository->updateStatus(
                $payment,
                Payment::STATUS_APPROVED,
                $reviewer
            );

            // Send notification to client
            if ($updated->client) {
                try {
                    $updated->client->notify(new \App\Notifications\PaymentApprovedNotification($updated));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Failed sending payment approved notification to client {$updated->client->id}: " . $e->getMessage(), ['exception' => $e]);
                }
            }

            return $updated;
        });
    }

    /**
     * Reject payment submission with reason and notify client.
     */
    public function rejectPayment(Payment $payment, User $reviewer, string $reason): Payment
    {
        return DB::transaction(function () use ($payment, $reviewer, $reason) {
            $updated = $this->paymentRepository->updateStatus(
                $payment,
                Payment::STATUS_REJECTED,
                $reviewer,
                $reason
            );

            // Send notification to client
            if ($updated->client) {
                try {
                    $updated->client->notify(new \App\Notifications\PaymentRejectedNotification($updated));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error("Failed sending payment rejected notification to client {$updated->client->id}: " . $e->getMessage(), ['exception' => $e]);
                }
            }

            return $updated;
        });
    }
}
