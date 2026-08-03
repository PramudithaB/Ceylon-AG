<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface
{
    /**
     * Get paginated list of clients with search & filter parameters.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find client by ID.
     */
    public function findById(int|string $id): ?User;

    /**
     * Create a new client record.
     */
    public function create(array $data): User;

    /**
     * Update client record.
     */
    public function update(User $user, array $data): bool;

    /**
     * Update client status.
     */
    public function updateStatus(User $user, string $status): bool;

    /**
     * Delete client record.
     */
    public function delete(User $user): bool;

    /**
     * Count clients by status.
     */
    public function countByStatus(string $status): int;

    /**
     * Get all active & approved clients.
     */
    public function getAllApproved(): \Illuminate\Database\Eloquent\Collection;
}
