<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefPaymentController extends Controller
{
    /**
     * Display list of client payments.
     */
    public function index(Request $request)
    {
        $refUser = Auth::user();
        $assignedClientIds = $refUser->assignedClients()->pluck('id');
        if ($assignedClientIds->isEmpty()) {
            $assignedClientIds = User::where('role', User::ROLE_CLIENT)->pluck('id');
        }

        $query = Payment::whereIn('client_id', $assignedClientIds)->with('client');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest('payment_date')->paginate(12)->withQueryString();

        return view('ref.payments.index', compact('payments'));
    }

    /**
     * Display specific payment detail.
     */
    public function show(Payment $payment)
    {
        $payment->load(['client', 'reviewer']);

        return view('ref.payments.show', compact('payment'));
    }
}
