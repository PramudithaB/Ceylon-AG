<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StorePaymentRequest;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Display a listing of client payment history.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $filters = [
            'status' => $request->get('status', 'all'),
            'search' => $request->get('search'),
        ];

        $payments = $this->paymentService->getClientPayments($user, $filters, 15);

        return view('payments.index', [
            'payments' => $payments,
            'filters' => $filters,
        ]);
    }

    /**
     * Show form for submitting a new bank payment.
     */
    public function create(): View
    {
        return view('payments.create');
    }

    /**
     * Store newly submitted payment and screenshot slip.
     */
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $file = $request->file('payment_screenshot');

        $payment = $this->paymentService->submitPayment(
            $request->validated(),
            $file,
            $request->user()
        );

        flash_message(
            "Payment submission (#{$payment->payment_number}) for LKR " . number_format((float) $payment->amount, 2) . " received and sent for administrative verification.",
            'success'
        );

        return redirect()->route('payments.index');
    }

    /**
     * Display detailed payment receipt voucher.
     */
    public function show(int $id): View
    {
        $payment = $this->paymentService->getPaymentById($id);

        // Security check: Client can only view their own payment
        if ($payment->client_id !== auth()->id()) {
            abort(403, 'Unauthorized access to payment voucher.');
        }

        return view('payments.show', [
            'payment' => $payment,
        ]);
    }
}
