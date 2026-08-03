<?php

namespace App\Repositories;

use App\Models\ProductAssignment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductAssignmentRepository implements ProductAssignmentRepositoryInterface
{
    /**
     * Get paginated assignments with filters.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = ProductAssignment::with(['client', 'product.category', 'assignedBy'])->latest('assigned_at');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('assignment_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        if (! empty($filters['client_id']) && $filters['client_id'] !== 'all') {
            $query->where('client_id', $filters['client_id']);
        }

        if (! empty($filters['product_id']) && $filters['product_id'] !== 'all') {
            $query->where('product_id', $filters['product_id']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get assignments for a specific client.
     */
    public function getByClient(User $client, int $perPage = 10): LengthAwarePaginator
    {
        return ProductAssignment::with(['product.category', 'assignedBy'])
            ->where('client_id', $client->id)
            ->latest('assigned_at')
            ->paginate($perPage);
    }

    /**
     * Find by ID.
     */
    public function findById(int|string $id): ?ProductAssignment
    {
        return ProductAssignment::with(['client', 'product.category', 'assignedBy'])->find($id);
    }

    /**
     * Create assignment record.
     */
    public function create(array $data): ProductAssignment
    {
        return ProductAssignment::create($data);
    }
}
