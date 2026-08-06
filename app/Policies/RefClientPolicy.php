<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class RefClientPolicy
{
    /**
     * Determine whether the Ref user can view the assigned client workspace.
     */
    public function view(User $user, User $client): Response
    {
        if (! $user->isRef() && ! $user->hasRole('Ref')) {
            return Response::deny('Only Sales Representatives can access client CRM workspaces.');
        }

        if ($client->ref_id !== $user->id) {
            return Response::deny('You are only authorized to access clients assigned directly to you.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the Ref user can record payments for the assigned client.
     */
    public function recordPayment(User $user, User $client): Response
    {
        return $this->view($user, $client);
    }

    /**
     * Determine whether the Ref user can create stock requests for the assigned client.
     */
    public function createStockRequest(User $user, User $client): Response
    {
        return $this->view($user, $client);
    }

    /**
     * Determine whether the Ref user can log notes for the assigned client.
     */
    public function addNote(User $user, User $client): Response
    {
        return $this->view($user, $client);
    }
}
