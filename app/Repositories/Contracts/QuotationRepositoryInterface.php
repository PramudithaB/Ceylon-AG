<?php

namespace App\Repositories\Contracts;

use App\Models\Quotation;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuotationRepositoryInterface
{
    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Quotation;

    public function findByNumber(string $number): ?Quotation;

    public function create(array $data): Quotation;

    public function update(Quotation $quotation, array $data): Quotation;

    public function delete(Quotation $quotation): bool;

    public function duplicate(Quotation $quotation, int $userId): Quotation;

    public function getDashboardCounts(): array;

    public function getClientQuotations(int $clientId, int $perPage = 10): LengthAwarePaginator;
}
