<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function getForClientPaginated(User $client, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Payment::where('client_id', $client->id)->latest('created_at');

        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Payment::with(['client', 'reviewer'])->latest('created_at');

        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $query->where('client_id', $filters['client_id']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%");
                  });
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): Payment
    {
        return Payment::with(['client', 'reviewer'])->findOrFail($id);
    }

    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function updateStatus(Payment $payment, string $status, User $reviewer, ?string $reason = null): Payment
    {
        $payment->update([
            'status' => $status,
            'reviewed_by' => $reviewer->id,
            'rejection_reason' => $status === Payment::STATUS_REJECTED ? $reason : null,
            'reviewed_at' => now(),
        ]);

        return $payment->fresh();
    }
}
