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
     * Display detailed profile of a client (Redirects to Single Client Workspace).
     */
    public function show(User $client)
    {
        return redirect()->route('ref.dashboard', ['client_id' => $client->id]);
    }
}
