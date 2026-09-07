<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\ClientSale;
use App\Models\Payment;
use App\Models\ProductAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefClientController extends Controller
{
    /**
     * Display a listing of assigned clients.
     */
    public function index(Request $request)
    {
        $refUser = Auth::user();

        $query = User::where('role', User::ROLE_CLIENT);

        // Filter by assigned to this ref if assignments exist, otherwise display all active clients
        $assignedCount = $refUser->assignedClients()->count();
        if ($assignedCount > 0) {
            $query->where('ref_id', $refUser->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%");
            });
        }

        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

        return view('ref.clients.index', compact('clients'));
    }

    /**
     * Show form for Ref to register a new Client.
     */
    public function create()
    {
        $provinces = \App\Support\Locations::getProvinces();
        $districts = \App\Support\Locations::getAllDistricts();
        $provinceDistricts = \App\Support\Locations::getHierarchy();

        return view('ref.clients.create', compact('provinces', 'districts', 'provinceDistricts'));
    }

    /**
     * Handle Client Registration submitted by Ref.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        if (! \App\Support\Locations::isValidPair($request->province, $request->district)) {
            return redirect()->back()
                ->withErrors(['district' => "The selected district '{$request->district}' does not belong to the selected province '{$request->province}'."])
                ->withInput();
        }

        $refUser = Auth::user();
        $fullName = trim("{$request->first_name} {$request->last_name}");

        $client = User::create([
            'name' => $fullName,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'business_name' => $request->business_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'province' => $request->province,
            'district' => $request->district,
            'address' => $request->address,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => User::ROLE_CLIENT, // Backend strictly enforces client role
            'ref_id' => $refUser->id,    // Connected to authenticated Ref
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        try {
            \Spatie\Permission\Models\Role::findOrCreate('Client', 'web');
            $client->assignRole('Client');
        } catch (\Throwable $e) {
            // Continue if role already assigned or table unavailable
        }

        // Make newly created client active in session immediately
        session(['active_client_id' => $client->id]);

        flash_message("Client '{$client->business_name}' ({$client->name}) registered successfully and assigned to your portfolio.", 'success');

        return redirect()->route('ref.dashboard', ['client_id' => $client->id])
            ->with('success', "Client '{$client->business_name}' ({$client->name}) registered successfully and assigned to your portfolio.");
    }

    /**
     * Display detailed profile of a client (Redirects to Single Client Workspace).
     */
    public function show(User $client)
    {
        return redirect()->route('ref.dashboard', ['client_id' => $client->id]);
    }
}
