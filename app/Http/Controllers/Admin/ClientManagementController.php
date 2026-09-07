<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Http\Requests\Admin\UpdateClientRequest;
use App\Models\User;
use App\Services\ClientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ClientManagementController extends Controller
{
    protected array $provinces = [
        'Western', 'Central', 'Southern', 'Northern', 'Eastern',
        'North Western', 'North Central', 'Uva', 'Sabaragamuwa',
    ];

    protected array $districts = [
        'Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya',
        'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar',
        'Vavuniya', 'Mullaitivu', 'Batticaloa', 'Ampara', 'Trincomalee',
        'Kurunegala', 'Puttalam', 'Anuradhapura', 'Polonnaruwa', 'Badulla',
        'Moneragala', 'Ratnapura', 'Kegalle',
    ];

    public function __construct(
        protected ClientService $clientService
    ) {}

    /**
     * Display a listing of client accounts with search and status/district filters.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status', 'all'),
            'district' => $request->get('district', 'all'),
            'province' => $request->get('province', 'all'),
        ];

        $clients = $this->clientService->getClients($filters, 15);
        $counts = $this->clientService->getClientCounts();
        $refs = $this->getActiveRefs();
        $allClients = $this->getAllSelectableClients();

        return view('admin.clients.index', [
            'clients' => $clients,
            'filters' => $filters,
            'counts' => $counts,
            'districts' => $this->districts,
            'provinces' => $this->provinces,
            'refs' => $refs,
            'allClients' => $allClients,
        ]);
    }

    /**
     * Display detailed Client Profile page.
     */
    public function show(User $client): View
    {
        Gate::authorize('viewAny', User::class);
        $refs = $this->getActiveRefs();

        return view('admin.clients.show', [
            'client' => $client->load('salesRep'),
            'refs' => $refs,
        ]);
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        Gate::authorize('viewAny', User::class);
        $refs = $this->getActiveRefs();

        return view('admin.clients.create', [
            'provinces' => $this->provinces,
            'districts' => $this->districts,
            'refs' => $refs,
        ]);
    }

    /**
     * Store a newly created client in storage using StoreClientRequest.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $photo = $request->file('photo');

        $client = $this->clientService->createClient($validated, $photo);

        flash_message("Client account for {$client->full_name} created successfully!", 'success');

        return redirect()->route('admin.clients.show', $client);
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(User $client): View
    {
        Gate::authorize('update', $client);
        $refs = $this->getActiveRefs();

        return view('admin.clients.edit', [
            'client' => $client->load('salesRep'),
            'provinces' => $this->provinces,
            'districts' => $this->districts,
            'refs' => $refs,
        ]);
    }

    /**
     * Assign / Reassign a Client to a Sales Representative (Ref).
     */
    public function assignRef(Request $request, ?User $client = null): RedirectResponse
    {
        $clientId = $client ? $client->id : ($request->input('client_id') ?? $request->route('client'));
        $targetClient = User::findOrFail($clientId);

        Gate::authorize('update', $targetClient);

        // Security check: Target MUST be strictly a Client
        if ($targetClient->role !== User::ROLE_CLIENT || $targetClient->isAdmin() || $targetClient->isRef() || $targetClient->hasAnyRole(['Admin', 'Super Admin', 'Ref', 'admin', 'ref'])) {
            return redirect()->back()
                ->withErrors(['client_id' => 'Only users with the client role can be assigned. Admin and Ref users cannot be assigned as clients.'])
                ->withInput();
        }

        // Validate ref_id
        $request->validate([
            'ref_id' => ['nullable', 'exists:users,id'],
        ]);

        $refId = $request->input('ref_id');

        if ($refId) {
            $refUser = User::findOrFail($refId);

            // Security check: Target MUST be strictly a Ref
            if ($refUser->role !== User::ROLE_REF || $refUser->isAdmin() || $refUser->isClient() || $refUser->hasAnyRole(['Admin', 'Super Admin', 'admin', 'Client', 'client'])) {
                return redirect()->back()
                    ->withErrors(['ref_id' => 'The selected recipient must be a user with the Ref role. Admins and Clients cannot be selected as Refs.'])
                    ->withInput();
            }

            $targetClient->ref_id = $refUser->id;
            $targetClient->save();

            flash_message("Client '{$targetClient->full_name}' has been successfully assigned to Ref '{$refUser->full_name}'.", 'success');
        } else {
            // Unassign
            $targetClient->ref_id = null;
            $targetClient->save();

            flash_message("Client '{$targetClient->full_name}' has been unassigned from any Ref.", 'info');
        }

        return redirect()->back();
    }

    /**
     * Retrieve all active Ref users for assignment selectors.
     */
    protected function getActiveRefs()
    {
        return User::where('role', User::ROLE_REF)
            ->whereNotIn('role', [User::ROLE_ADMIN, User::ROLE_CLIENT])
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Admin', 'Super Admin', 'admin']);
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Retrieve all selectable clients for the admin assignment modal.
     */
    protected function getAllSelectableClients()
    {
        return User::where('role', User::ROLE_CLIENT)
            ->whereNotIn('role', [User::ROLE_ADMIN, User::ROLE_REF])
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Admin', 'Super Admin', 'Ref', 'ref', 'admin']);
            })
            ->with('salesRep')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Update the specified client in storage using UpdateClientRequest.
     */
    public function update(UpdateClientRequest $request, User $client): RedirectResponse
    {
        $validated = $request->validated();
        $photo = $request->file('photo');

        $this->clientService->updateClient($client, $validated, $photo);

        flash_message("Client account for {$client->full_name} updated successfully!", 'success');

        return redirect()->route('admin.clients.show', $client);
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(User $client): RedirectResponse
    {
        Gate::authorize('delete', $client);

        $name = $client->full_name;
        $this->clientService->deleteClient($client);

        flash_message("Client account for {$name} deleted permanently.", 'warning');

        return redirect()->route('admin.clients.index');
    }

    /**
     * Approve client account.
     */
    public function approve(User $client): RedirectResponse
    {
        Gate::authorize('approve', $client);

        $this->clientService->approveClient($client);

        flash_message("Client account for {$client->full_name} approved and activation email sent!", 'success');

        return redirect()->back();
    }

    /**
     * Reject client registration.
     */
    public function reject(User $client): RedirectResponse
    {
        Gate::authorize('approve', $client);

        $this->clientService->rejectClient($client);

        flash_message("Client registration for {$client->full_name} has been rejected.", 'warning');

        return redirect()->back();
    }

    /**
     * Activate client account.
     */
    public function activate(User $client): RedirectResponse
    {
        Gate::authorize('approve', $client);

        $this->clientService->activateClient($client);

        flash_message("Client account for {$client->full_name} has been activated.", 'success');

        return redirect()->back();
    }

    /**
     * Deactivate client account.
     */
    public function deactivate(User $client): RedirectResponse
    {
        Gate::authorize('approve', $client);

        $this->clientService->deactivateClient($client);

        flash_message("Client account for {$client->full_name} has been deactivated.", 'info');

        return redirect()->back();
    }
}
