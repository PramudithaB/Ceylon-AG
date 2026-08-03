<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaymentRepositoryInterface
{
    public function getForClientPaginated(User $client, array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): Payment;
    public function create(array $data): Payment;
    public function updateStatus(Payment $payment, string $status, User $reviewer, ?string $reason = null): Payment;
}
