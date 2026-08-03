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

        return view('admin.clients.index', [
            'clients' => $clients,
            'filters' => $filters,
            'counts' => $counts,
            'districts' => $this->districts,
            'provinces' => $this->provinces,
        ]);
    }

    /**
     * Display detailed Client Profile page.
     */
    public function show(User $client): View
    {
        Gate::authorize('viewAny', User::class);

        return view('admin.clients.show', [
            'client' => $client,
        ]);
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('admin.clients.create', [
            'provinces' => $this->provinces,
            'districts' => $this->districts,
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

        return view('admin.clients.edit', [
            'client' => $client,
            'provinces' => $this->provinces,
            'districts' => $this->districts,
        ]);
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
