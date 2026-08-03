<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectPaymentRequest;
use App\Repositories\ClientRepositoryInterface;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected ClientRepositoryInterface $clientRepository
    ) {}

    /**
     * Display listing of all client payment submissions.
     */
    public function index(Request $request): View
    {
        $filters = [
            'status' => $request->get('status', 'all'),
            'client_id' => $request->get('client_id', 'all'),
            'search' => $request->get('search'),
        ];

        $payments = $this->paymentService->getAllPayments($filters, 15);
        $clients = $this->clientRepository->getAllApproved();

        return view('admin.payments.index', [
            'payments' => $payments,
            'filters' => $filters,
            'clients' => $clients,
        ]);
    }

    /**
     * Display detailed payment review voucher.
     */
    public function show(int $id): View
    {
        $payment = $this->paymentService->getPaymentById($id);

        return view('admin.payments.show', [
            'payment' => $payment,
        ]);
    }

    /**
     * Approve client payment submission.
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $payment = $this->paymentService->getPaymentById($id);

        $this->paymentService->approvePayment($payment, $request->user());

        flash_message("Payment (#{$payment->payment_number}) has been approved successfully.", 'success');

        return redirect()->back();
    }

    /**
     * Reject client payment submission.
     */
    public function reject(RejectPaymentRequest $request, int $id): RedirectResponse
    {
        $payment = $this->paymentService->getPaymentById($id);

        $this->paymentService->rejectPayment(
            $payment,
            $request->user(),
            $request->validated()['rejection_reason']
        );

        flash_message("Payment (#{$payment->payment_number}) has been rejected.", 'warning');

        return redirect()->back();
    }
}
