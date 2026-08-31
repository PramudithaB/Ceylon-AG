<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ref\CreateStockRequest;
use App\Http\Requests\Ref\RecordPaymentRequest;
use App\Http\Requests\Ref\StoreClientNoteRequest;
use App\Models\Product;
use App\Models\User;
use App\Services\RefCrmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RefClientCrmController extends Controller
{
    protected RefCrmService $crmService;

    public function __construct(RefCrmService $crmService)
    {
        $this->crmService = $crmService;
    }

    /**
     * Check if current user is authorized for client.
     */
    protected function authorizeRefAccess(User $client): void
    {
        $user = auth()->user();
        if (! $user || (! $user->isRef() && ! $user->isAdmin())) {
            abort(403, 'Unauthorized access to client workspace.');
        }

        if (! ($client->isClient() || $client->role === User::ROLE_CLIENT || $client->hasRole('Client'))) {
            abort(403, 'Invalid recipient. Only client accounts can receive stock requests or be managed by a Ref.');
        }
    }

    /**
     * AJAX Endpoint to switch selected client workspace dynamically.
     */
    public function showAjax(User $client)
    {
        $this->authorizeRefAccess($client);

        $refUser = auth()->user();
        $workspaceData = $this->crmService->getClientFullWorkspaceData($client, $refUser);
        $products = Product::where('status', 'active')->orderBy('name')->get();

        $html = view('ref.partials.client-workspace-panel', array_merge([
            'selectedClient' => $client,
            'products' => $products,
        ], $workspaceData))->render();

        return response()->json([
            'success' => true,
            'client_id' => $client->id,
            'business_name' => $client->business_name ?? $client->name,
            'html' => $html,
        ]);
    }

    /**
     * Store new payment collected from client.
     */
    public function storePayment(RecordPaymentRequest $request, User $client)
    {
        $this->authorizeRefAccess($client);

        $refUser = auth()->user();
        $payment = $this->crmService->recordClientPayment($client, $refUser, $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Payment of LKR " . number_format((float) $payment->amount, 2) . " recorded successfully! Status: Pending Verification.",
            ]);
        }

        return redirect()->route('ref.dashboard', ['client_id' => $client->id])
            ->with('success', "Payment of LKR " . number_format((float) $payment->amount, 2) . " recorded successfully! Status: Pending Verification.");
    }

    /**
     * Submit stock request for client.
     */
    public function storeStockRequest(CreateStockRequest $request, User $client)
    {
        $this->authorizeRefAccess($client);

        $refUser = auth()->user();
        $stockRequest = $this->crmService->submitClientStockRequest($client, $refUser, $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Stock request {$stockRequest->request_number} submitted to Admin successfully!",
            ]);
        }

        return redirect()->route('ref.dashboard', ['client_id' => $client->id])
            ->with('success', "Stock request {$stockRequest->request_number} submitted to Admin successfully!");
    }

    /**
     * Add note for client.
     */
    public function storeNote(StoreClientNoteRequest $request, User $client)
    {
        $this->authorizeRefAccess($client);

        $refUser = auth()->user();
        $note = $this->crmService->addClientNote($client, $refUser, $request->validated()['content']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Note added to client timeline.',
            ]);
        }

        return redirect()->route('ref.dashboard', ['client_id' => $client->id])
            ->with('success', 'Note added to client timeline.');
    }

    /**
     * Render printable client CRM summary report.
     */
    public function printSummary(User $client)
    {
        $this->authorizeRefAccess($client);

        $refUser = auth()->user();
        $workspaceData = $this->crmService->getClientFullWorkspaceData($client, $refUser);

        return view('ref.clients.print-summary', array_merge([
            'client' => $client,
            'refUser' => $refUser,
        ], $workspaceData));
    }
}
