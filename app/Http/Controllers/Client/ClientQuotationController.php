<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Quotation;
use App\Repositories\Contracts\QuotationRepositoryInterface;
use App\Services\QuotationPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientQuotationController extends Controller
{
    protected QuotationRepositoryInterface $repository;
    protected QuotationPdfService $pdfService;

    public function __construct(
        QuotationRepositoryInterface $repository,
        QuotationPdfService $pdfService
    ) {
        $this->repository = $repository;
        $this->pdfService = $pdfService;
    }

    /**
     * Display list of quotations issued to logged in client.
     */
    public function index()
    {
        $clientId = Auth::id();
        $quotations = $this->repository->getClientQuotations($clientId, 10);

        return view('client.quotations.index', compact('quotations'));
    }

    /**
     * Display detailed read-only view of a client quotation.
     */
    public function show(Quotation $quotation)
    {
        // Enforce ownership check
        if ($quotation->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access to quotation.');
        }

        $quotation->load(['items.product', 'creator']);
        $settings = CompanySetting::getSettings();

        return view('client.quotations.show', compact('quotation', 'settings'));
    }

    /**
     * Download PDF version for client.
     */
    public function downloadPdf(Quotation $quotation)
    {
        if ($quotation->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access to quotation PDF.');
        }

        return $this->pdfService->downloadPdfResponse($quotation);
    }

    /**
     * Render printable A4 portrait view for client.
     */
    public function print(Quotation $quotation)
    {
        if ($quotation->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access to quotation print view.');
        }

        $quotation->load(['items.product', 'creator']);
        $settings = CompanySetting::getSettings();

        return view('admin.quotations.print', compact('quotation', 'settings'));
    }
}
