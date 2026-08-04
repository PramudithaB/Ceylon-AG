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
     * Display detailed profile of a client.
     */
    public function show(User $client)
    {
        // Ensure user is a client
        if ($client->role !== User::ROLE_CLIENT && ! $client->hasRole('Client')) {
            abort(404);
        }

        // Product Assignments for this client
        $productAssignments = ProductAssignment::where('client_id', $client->id)
            ->with('product')
            ->latest('assigned_at')
            ->get();

        // Sales history
        $sales = ClientSale::where('client_id', $client->id)
            ->with('product')
            ->latest('sold_at')
            ->paginate(5, ['*'], 'sales_page');

        // Payments status
        $payments = Payment::where('client_id', $client->id)
            ->latest('payment_date')
            ->paginate(5, ['*'], 'payments_page');

        // Financial summary
        $totalPurchases = ClientSale::where('client_id', $client->id)->sum('total_amount');
        $totalPaid = Payment::where('client_id', $client->id)->where('status', 'approved')->sum('amount');
        $pendingPayments = Payment::where('client_id', $client->id)->where('status', 'pending')->sum('amount');

        return view('ref.clients.show', compact('client', 'productAssignments', 'sales', 'payments', 'totalPurchases', 'totalPaid', 'pendingPayments'));
    }
}
