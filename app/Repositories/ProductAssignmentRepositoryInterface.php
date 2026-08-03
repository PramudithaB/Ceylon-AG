<?php

namespace App\Repositories;

use App\Models\ProductAssignment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductAssignmentRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function getByClient(User $client, int $perPage = 10): LengthAwarePaginator;
    public function findById(int|string $id): ?ProductAssignment;
    public function create(array $data): ProductAssignment;
}
