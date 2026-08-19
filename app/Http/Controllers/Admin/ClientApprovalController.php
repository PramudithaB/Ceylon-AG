<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountApprovedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ClientApprovalController extends Controller
{
    /**
     * Display a listing of client accounts with status filter.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $status = $request->get('status', 'all');

        $query = User::where(function ($q) {
            $q->where('role', User::ROLE_CLIENT)
              ->orWhereHas('roles', fn ($r) => $r->where('name', 'Client'));
        })->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $clients = $query->paginate(15);
        $pendingCount = User::where(function ($q) {
            $q->where('role', User::ROLE_CLIENT)
              ->orWhereHas('roles', fn ($r) => $r->where('name', 'Client'));
        })->where('status', User::STATUS_PENDING)->count();
        $approvedCount = User::where(function ($q) {
            $q->where('role', User::ROLE_CLIENT)
              ->orWhereHas('roles', fn ($r) => $r->where('name', 'Client'));
        })->where('status', User::STATUS_APPROVED)->count();
        $rejectedCount = User::where(function ($q) {
            $q->where('role', User::ROLE_CLIENT)
              ->orWhereHas('roles', fn ($r) => $r->where('name', 'Client'));
        })->where('status', User::STATUS_REJECTED)->count();

        return view('admin.clients.index', compact('clients', 'status', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Approve a pending client account.
     */
    public function approve(User $user): RedirectResponse
    {
        Gate::authorize('approve', $user);

        $user->update([
            'status' => User::STATUS_APPROVED,
        ]);

        // Send activation email notification
        $user->notify(new AccountApprovedNotification());

        flash_message("Client account for {$user->full_name} ({$user->business_name}) has been approved and activated! Notification email sent.", 'success');

        return redirect()->back();
    }

    /**
     * Reject a client registration request.
     */
    public function reject(User $user): RedirectResponse
    {
        Gate::authorize('approve', $user);

        $user->update([
            'status' => User::STATUS_REJECTED,
        ]);

        flash_message("Client registration for {$user->full_name} has been rejected.", 'warning');

        return redirect()->back();
    }
}
