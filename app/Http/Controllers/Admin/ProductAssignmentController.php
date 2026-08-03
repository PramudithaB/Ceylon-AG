<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductAssignmentRequest;
use App\Models\ProductAssignment;
use App\Repositories\ClientRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Services\ProductAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductAssignmentController extends Controller
{
    public function __construct(
        protected ProductAssignmentService $assignmentService,
        protected ClientRepositoryInterface $clientRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display a listing of product assignments.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'client_id' => $request->get('client_id', 'all'),
            'product_id' => $request->get('product_id', 'all'),
        ];

        $assignments = $this->assignmentService->getAssignments($filters, 15);
        $clients = $this->clientRepository->getAllApproved();
        $products = $this->productRepository->getAllPaginated([], 100);

        return view('admin.product-assignments.index', [
            'assignments' => $assignments,
            'filters' => $filters,
            'clients' => $clients,
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product assignment.
     */
    public function create(Request $request): View
    {
        $clients = $this->clientRepository->getAllApproved();
        $products = $this->productRepository->getAllPaginated(['status' => 'active'], 100);

        $selectedClientId = $request->get('client_id');
        $selectedProductId = $request->get('product_id');

        return view('admin.product-assignments.create', [
            'clients' => $clients,
            'products' => $products,
            'selectedClientId' => $selectedClientId,
            'selectedProductId' => $selectedProductId,
        ]);
    }

    /**
     * Store a newly created product assignment in storage.
     */
    public function store(StoreProductAssignmentRequest $request): RedirectResponse
    {
        try {
            $assignment = $this->assignmentService->assignProduct(
                $request->validated(),
                $request->user()
            );

            flash_message(
                "Product '{$assignment->product->name}' successfully assigned to client '{$assignment->client->name}' (#{$assignment->assignment_number}).",
                'success'
            );

            return redirect()->route('admin.product-assignments.show', $assignment);
        } catch (InsufficientStockException $e) {
            flash_message($e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified product assignment.
     */
    public function show(ProductAssignment $productAssignment): View
    {
        $productAssignment->load(['client', 'product.category', 'assignedBy']);

        return view('admin.product-assignments.show', [
            'assignment' => $productAssignment,
        ]);
    }
}
