<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin']);
    }

    /**
     * Determine whether the user can approve clients.
     */
    public function approve(User $user, User $model): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin']);
    }

    /**
     * Determine whether the user can update the profile.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Super Admin') && $user->id !== $model->id;
    }
}
