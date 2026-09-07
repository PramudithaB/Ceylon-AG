<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * Get paginated list of clients with search & filter parameters.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::where(function ($q) {
            $q->where('role', User::ROLE_CLIENT)
              ->orWhereHas('roles', fn ($r) => $r->where('name', 'Client'));
        })->latest();

        // Search filter (Name, Business Name, NIC, Phone, Email)
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('nic', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // District filter
        if (! empty($filters['district']) && $filters['district'] !== 'all') {
            $query->where('district', $filters['district']);
        }

        // Province filter
        if (! empty($filters['province']) && $filters['province'] !== 'all') {
            $query->where('province', $filters['province']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Find client by ID.
     */
    public function findById(int|string $id): ?User
    {
        return User::where(function ($q) {
            $q->where('role', User::ROLE_CLIENT)
              ->orWhereHas('roles', fn ($r) => $r->where('name', 'Client'));
        })->find($id);
    }

    /**
     * Create a new client record.
     */
    public function create(array $data): User
    {
        $data['role'] = User::ROLE_CLIENT;
        $user = User::create($data);
        
        try {
            \Spatie\Permission\Models\Role::findOrCreate('Client', 'web');
            $user->assignRole('Client');
        } catch (\Throwable $e) {
            // Silently continue if role table not migrated yet
        }

        return $user;
    }

    /**
     * Update client record.
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Update client status.
     */
    public function updateStatus(User $user, string $status): bool
    {
        return $user->update(['status' => $status]);
    }

    /**
     * Delete client record.
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Count clients by status.
     */
    public function countByStatus(string $status): int
    {
        $query = User::where(function ($q) {
            $q->where('role', User::ROLE_CLIENT)
              ->orWhereHas('roles', fn ($r) => $r->where('name', 'Client'));
        });

        if ($status === 'all') {
            return $query->count();
        }

        return $query->where('status', $status)->count();
    }

    /**
     * Get all active & approved clients.
     */
    public function getAllApproved(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('role', User::ROLE_CLIENT)
            ->whereNotIn('role', [User::ROLE_ADMIN, User::ROLE_REF])
            ->whereDoesntHave('roles', fn ($r) => $r->whereIn('name', ['Admin', 'Super Admin', 'Ref', 'ref', 'admin']))
            ->whereIn('status', [User::STATUS_APPROVED, User::STATUS_ACTIVE])
            ->orderBy('name')
            ->get();
    }
}
