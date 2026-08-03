<?php

namespace App\Repositories;

use App\Models\ClientSale;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ClientSaleRepositoryInterface
{
    public function getForClientPaginated(User $client, array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): ClientSale;
    public function getClientSummary(User $client): array;
    public function getClientInventoryBreakdown(User $client): Collection;
    public function getSalesReportData(array $filters = []): array;
}
