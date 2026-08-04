<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotation\StoreQuotationRequest;
use App\Http\Requests\Quotation\UpdateQuotationRequest;
use App\Models\CompanySetting;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\User;
use App\Repositories\Contracts\QuotationRepositoryInterface;
use App\Services\QuotationPdfService;
use App\Services\QuotationService;
use Illuminate\Http\Request;

class AdminQuotationController extends Controller
{
    protected QuotationRepositoryInterface $repository;
    protected QuotationService $quotationService;
    protected QuotationPdfService $pdfService;

    public function __construct(
        QuotationRepositoryInterface $repository,
        QuotationService $quotationService,
        QuotationPdfService $pdfService
    ) {
        $this->repository = $repository;
        $this->quotationService = $quotationService;
        $this->pdfService = $pdfService;
    }

    /**
     * Quotations Module Dashboard with KPI Summary Cards.
     */
    public function dashboard()
    {
        $counts = $this->repository->getDashboardCounts();
        $recentQuotations = Quotation::with(['client', 'creator'])->latest()->take(5)->get();

        return view('admin.quotations.dashboard', compact('counts', 'recentQuotations'));
    }

    /**
     * All Quotations Listing Page with Search & Status Filters.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'date_from', 'date_to']);
        $quotations = $this->repository->getPaginated($filters, 10);
        $counts = $this->repository->getDashboardCounts();

        return view('admin.quotations.index', compact('quotations', 'counts', 'filters'));
    }

    /**
     * Render Create Quotation Form.
     */
    public function create()
    {
        $clients = User::where('role', User::ROLE_CLIENT)->orderBy('name')->get();
        $products = Product::where('status', 'active')->orderBy('name')->get();
        $settings = CompanySetting::getSettings();
        $autoNumber = Quotation::generateQuotationNumber();

        return view('admin.quotations.create', compact('clients', 'products', 'settings', 'autoNumber'));
    }

    /**
     * Store new Quotation in Database.
     */
    public function store(StoreQuotationRequest $request)
    {
        $quotation = $this->quotationService->createQuotation($request->validated(), auth()->id());

        flash_message("Quotation {$quotation->quotation_number} created successfully!", 'success');

        return redirect()->route('admin.quotations.show', $quotation->id);
    }

    /**
     * View detailed Quotation page.
     */
    public function show(Quotation $quotation)
    {
        $quotation->load(['items.product', 'client', 'creator']);
        $settings = CompanySetting::getSettings();

        return view('admin.quotations.show', compact('quotation', 'settings'));
    }

    /**
     * Render Edit Quotation Form.
     */
    public function edit(Quotation $quotation)
    {
        $quotation->load(['items']);
        $clients = User::where('role', User::ROLE_CLIENT)->orderBy('name')->get();
        $products = Product::where('status', 'active')->orderBy('name')->get();
        $settings = CompanySetting::getSettings();

        return view('admin.quotations.edit', compact('quotation', 'clients', 'products', 'settings'));
    }

    /**
     * Update existing Quotation in Database.
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        $this->quotationService->updateQuotation($quotation, $request->validated());

        flash_message("Quotation {$quotation->quotation_number} updated successfully!", 'success');

        return redirect()->route('admin.quotations.show', $quotation->id);
    }

    /**
     * Delete Quotation.
     */
    public function destroy(Quotation $quotation)
    {
        $number = $quotation->quotation_number;
        $this->quotationService->deleteQuotation($quotation);

        flash_message("Quotation {$number} deleted successfully!", 'success');

        return redirect()->route('admin.quotations.index');
    }

    /**
     * Render A4 Printable Corporate View.
     */
    public function print(Quotation $quotation)
    {
        $quotation->load(['items.product', 'client', 'creator']);
        $settings = CompanySetting::getSettings();

        return view('admin.quotations.print', compact('quotation', 'settings'));
    }

    /**
     * Download PDF file response.
     */
    public function downloadPdf(Quotation $quotation)
    {
        return $this->pdfService->downloadPdfResponse($quotation);
    }

    /**
     * Email Quotation to Client with PDF attachment.
     */
    public function sendEmail(Request $request, Quotation $quotation)
    {
        $email = $request->input('recipient_email', $quotation->email ?? $quotation->client?->email);

        if (!$email) {
            flash_message('Client email address is missing. Please update quotation email first.', 'error');
            return redirect()->back();
        }

        $sent = $this->quotationService->emailQuotation($quotation, $email);

        if ($sent) {
            flash_message("Quotation {$quotation->quotation_number} emailed successfully to {$email}!", 'success');
        } else {
            flash_message("Failed to send quotation email. Please check email address.", 'error');
        }

        return redirect()->back();
    }

    /**
     * Duplicate Quotation as a new Draft.
     */
    public function duplicate(Quotation $quotation)
    {
        $newQuotation = $this->quotationService->duplicateQuotation($quotation, auth()->id());

        flash_message("Quotation duplicated successfully as {$newQuotation->quotation_number}!", 'success');

        return redirect()->route('admin.quotations.edit', $newQuotation->id);
    }
}
