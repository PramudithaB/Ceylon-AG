<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Services\RefCrmService;
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

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $payments = $query->latest('payment_date')->latest('id')->paginate(12)->withQueryString();
        $clients = User::whereIn('id', $assignedClientIds)->get();

        return view('ref.payments.index', compact('payments', 'clients'));
    }

    /**
     * Show form to submit a new payment collected from a client.
     */
    public function create(Request $request)
    {
        $refUser = Auth::user();
        $assignedClients = $refUser->assignedClients()->where('status', User::STATUS_APPROVED)->get();
        if ($assignedClients->isEmpty()) {
            $assignedClients = User::where('role', User::ROLE_CLIENT)->whereIn('status', [User::STATUS_APPROVED, User::STATUS_ACTIVE])->get();
        }

        $activeClientId = $request->input('client_id', session('active_client_id'));
        $selectedClient = null;

        if ($activeClientId) {
            $selectedClient = $assignedClients->firstWhere('id', (int) $activeClientId)
                ?? User::where('id', (int) $activeClientId)->first();
        }

        return view('ref.payments.create', compact('assignedClients', 'selectedClient'));
    }

    /**
     * Store new payment collected from a client.
     */
    public function store(Request $request, RefCrmService $crmService)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'in:cash,bank_transfer,cheque,credit_card,online'],
            'payment_date' => ['nullable', 'date'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'cheque_number' => ['nullable', 'string', 'max:100'],
            'card_last_four' => ['nullable', 'string', 'max:4'],
            'remarks' => ['nullable', 'string', 'max:500'],
            'payment_screenshot' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:5120'],
        ]);

        $client = User::findOrFail($validated['client_id']);
        if (! ($client->isClient() || $client->role === User::ROLE_CLIENT || $client->hasRole('Client'))) {
            return redirect()->back()
                ->withErrors(['client_id' => 'Payments can only be recorded for client accounts.'])
                ->withInput();
        }

        $payment = $crmService->recordClientPayment($client, Auth::user(), $validated);

        flash_message("Payment #{$payment->payment_number} of LKR " . number_format($payment->amount, 2) . " submitted for Admin verification.", 'success');

        if ($request->input('source') === 'dashboard' || $request->has('from_dashboard')) {
            return redirect()->route('ref.dashboard', ['client_id' => $client->id])
                ->with('success', "Payment #{$payment->payment_number} of LKR " . number_format($payment->amount, 2) . " recorded successfully!");
        }

        return redirect()->route('ref.payments.index');
    }

    /**
     * Display specific payment detail.
     */
    public function show(Payment $payment)
    {
        $payment->load(['client', 'reviewer', 'collector']);

        return view('ref.payments.show', compact('payment'));
    }
}
