<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AccountApprovedNotification;
use App\Repositories\ClientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientService
{
    public function __construct(
        protected ClientRepositoryInterface $clientRepository
    ) {}

    /**
     * Get paginated clients matching search & status filters.
     */
    public function getClients(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->clientRepository->getAllPaginated($filters, $perPage);
    }

    /**
     * Get counts by status.
     */
    public function getClientCounts(): array
    {
        return [
            'all' => $this->clientRepository->countByStatus('all'),
            'pending' => $this->clientRepository->countByStatus(User::STATUS_PENDING),
            'approved' => $this->clientRepository->countByStatus(User::STATUS_APPROVED),
            'active' => $this->clientRepository->countByStatus(User::STATUS_ACTIVE),
            'deactivated' => $this->clientRepository->countByStatus(User::STATUS_DEACTIVATED),
            'rejected' => $this->clientRepository->countByStatus(User::STATUS_REJECTED),
        ];
    }

    /**
     * Find client by ID.
     */
    public function getClient(int|string $id): ?User
    {
        return $this->clientRepository->findById($id);
    }

    /**
     * Create client account with optional photo upload.
     */
    public function createClient(array $data, ?UploadedFile $photo = null): User
    {
        if ($photo) {
            $data['profile_photo_path'] = $photo->store('profile-photos', 'public');
        }

        $data['name'] = "{$data['first_name']} {$data['last_name']}";
        $data['password'] = Hash::make($data['password']);
        $data['status'] = $data['status'] ?? User::STATUS_PENDING;

        return $this->clientRepository->create($data);
    }

    /**
     * Update client profile details & optional photo upload.
     */
    public function updateClient(User $user, array $data, ?UploadedFile $photo = null): bool
    {
        if ($photo) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $data['profile_photo_path'] = $photo->store('profile-photos', 'public');
        }

        if (isset($data['first_name']) && isset($data['last_name'])) {
            $data['name'] = "{$data['first_name']} {$data['last_name']}";
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->clientRepository->update($user, $data);
    }

    /**
     * Approve client account and send notification.
     */
    public function approveClient(User $user): bool
    {
        $updated = $this->clientRepository->updateStatus($user, User::STATUS_APPROVED);

        if ($updated) {
            $user->notify(new \App\Notifications\ClientApprovedNotification($user, 'approved'));
        }

        return $updated;
    }

    /**
     * Reject client registration.
     */
    public function rejectClient(User $user): bool
    {
        $updated = $this->clientRepository->updateStatus($user, User::STATUS_REJECTED);

        if ($updated) {
            $user->notify(new \App\Notifications\ClientApprovedNotification($user, 'rejected'));
        }

        return $updated;
    }

    /**
     * Activate client account.
     */
    public function activateClient(User $user): bool
    {
        return $this->clientRepository->updateStatus($user, User::STATUS_ACTIVE);
    }

    /**
     * Deactivate client account.
     */
    public function deactivateClient(User $user): bool
    {
        return $this->clientRepository->updateStatus($user, User::STATUS_DEACTIVATED);
    }

    /**
     * Delete client account.
     */
    public function deleteClient(User $user): bool
    {
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        return $this->clientRepository->delete($user);
    }
}
