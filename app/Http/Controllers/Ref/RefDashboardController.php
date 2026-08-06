<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\RefCrmService;
use Illuminate\Http\Request;

class RefDashboardController extends Controller
{
    protected RefCrmService $crmService;

    public function __construct(RefCrmService $crmService)
    {
        $this->crmService = $crmService;
    }

    /**
     * Render Single Split-Screen Client Workspace Dashboard.
     */
    public function index(Request $request)
    {
        $refUser = auth()->user();

        $search = $request->input('search');
        $status = $request->input('status');
        $district = $request->input('district');

        // Fetch all clients in system for top selector dropdown
        $allClients = $this->crmService->getAllClientsForSelector($search);

        $assignedClients = $this->crmService->getAssignedClients($refUser, $search, $status, $district);
        if ($assignedClients->isEmpty()) {
            $assignedClients = $allClients;
        }

        // Determine selected client
        $selectedClientId = $request->input('client_id');
        $selectedClient = null;

        if ($selectedClientId) {
            $selectedClient = $allClients->firstWhere('id', (int) $selectedClientId) 
                ?? \App\Models\User::where('id', (int) $selectedClientId)->first();
        }

        // Fetch full workspace data for selected client if available
        $workspaceData = null;
        if ($selectedClient) {
            $workspaceData = $this->crmService->getClientFullWorkspaceData($selectedClient, $refUser);
        }

        // Products list for Stock Request modal/form
        $products = Product::where('status', 'active')->orderBy('name')->get();

        return view('ref.dashboard', array_merge([
            'allClients' => $allClients,
            'assignedClients' => $assignedClients,
            'selectedClient' => $selectedClient,
            'products' => $products,
            'search' => $search,
            'status' => $status,
            'district' => $district,
        ], $workspaceData ?? []));
    }
}
