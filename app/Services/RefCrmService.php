<?php

namespace App\Services;

use App\Models\ClientNote;
use App\Models\ClientSale;
use App\Models\Payment;
use App\Models\ProductAssignment;
use App\Models\Quotation;
use App\Models\StockRequest;
use App\Models\User;
use App\Notifications\NewPaymentRecordedNotification;
use App\Notifications\NewStockRequestSubmittedNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class RefCrmService
{
    /**
     * Get all clients assigned to the logged-in Ref user with optional filtering.
     */
    public function getAssignedClients(User $refUser, ?string $search = null, ?string $status = null, ?string $district = null)
    {
        $query = $refUser->assignedClients()
            ->where(function ($q) {
                $q->where('role', User::ROLE_CLIENT)
                  ->orWhereHas('roles', function ($rq) {
                      $rq->where('name', 'client');
                  });
            });

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($status)) {
            $query->where('status', $status);
        }

        if (! empty($district)) {
            $query->where('district', $district);
        }

        return $query->orderBy('name', 'asc')->get()->map(function ($client) {
            $financials = $this->getClientFinancialSummary($client);
            $client->outstanding_balance = $financials['outstanding_balance'];
            $client->last_payment_date = $financials['last_payment_date'];
            $client->current_stock_status = $this->getClientStockStatus($client);
            return $client;
        });
    }

    /**
     * Get all clients in system for top selector dropdown with search support.
     */
    public function getAllClientsForSelector(?string $search = null)
    {
        $query = User::where(function ($q) {
            $q->where('role', User::ROLE_CLIENT)
              ->orWhereHas('roles', function ($rq) {
                  $rq->where('name', 'client');
              });
        });

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('business_name', 'asc')->orderBy('name', 'asc')->get();
    }

    /**
     * Get full CRM Workspace data package for a specific client.
     */
    public function getClientFullWorkspaceData(User $client, User $refUser): array
    {
        $financialSummary = $this->getClientFinancialSummary($client);
        $productStock = $this->getClientProductStock($client);
        $orderHistory = $this->getClientOrderHistory($client);
        $paymentHistory = $this->getClientPaymentHistory($client);
        $stockRequests = $this->getClientStockRequests($client);
        $notes = $this->getClientNotes($client);
        $timeline = $this->getClientActivityTimeline($client);

        // Calculate dashboard card metrics
        $dashboardCards = [
            'current_stock' => $productStock->sum('remaining_qty'),
            'products_assigned' => $productStock->sum('assigned_qty'),
            'products_remaining' => $productStock->sum('remaining_qty'),
            'total_product_value' => $financialSummary['total_given'],
            'total_paid' => $financialSummary['total_paid'],
            'outstanding_balance' => $financialSummary['outstanding_balance'],
            'todays_collection' => Payment::where('client_id', $client->id)
                ->whereDate('payment_date', Carbon::today())
                ->where('status', Payment::STATUS_APPROVED)
                ->sum('amount'),
            'last_payment' => $financialSummary['last_payment_amount'],
            'last_order_date' => $financialSummary['last_order_date'],
        ];

        // Sales summary metrics
        $salesQuery = ClientSale::where('client_id', $client->id);
        $todaysSales = (float) (clone $salesQuery)->whereDate('sold_at', Carbon::today())->sum('total_amount');
        $monthlySales = (float) (clone $salesQuery)->whereMonth('sold_at', Carbon::now()->month)->whereYear('sold_at', Carbon::now()->year)->sum('total_amount');
        $totalSales = (float) (clone $salesQuery)->sum('total_amount');
        $productsSold = (int) (clone $salesQuery)->sum('quantity');

        $salesSummary = [
            'todays_sales' => $todaysSales,
            'monthly_sales' => $monthlySales,
            'total_sales' => $totalSales,
            'products_sold' => $productsSold,
            'remaining_stock' => $productStock->sum('remaining_qty'),
        ];

        return [
            'client' => $client,
            'dashboard_cards' => $dashboardCards,
            'financial_summary' => $financialSummary,
            'sales_summary' => $salesSummary,
            'product_stock' => $productStock,
            'order_history' => $orderHistory,
            'payment_history' => $paymentHistory,
            'stock_requests' => $stockRequests,
            'notes' => $notes,
            'timeline' => $timeline,
        ];
    }

    /**
     * Calculate financial summary for a client.
     */
    public function getClientFinancialSummary(User $client): array
    {
        $totalGiven = ProductAssignment::where('client_id', $client->id)->sum('total_dealer_amount');
        
        $approvedPayments = Payment::where('client_id', $client->id)
            ->where('status', Payment::STATUS_APPROVED);
        
        $totalPaid = (float) $approvedPayments->sum('amount');
        $outstandingBalance = max(0, $totalGiven - $totalPaid);
        $currentCredit = $totalPaid > $totalGiven ? ($totalPaid - $totalGiven) : 0;

        $lastPayment = Payment::where('client_id', $client->id)
            ->where('status', Payment::STATUS_APPROVED)
            ->orderBy('payment_date', 'desc')
            ->first();

        $lastOrder = ClientSale::where('client_id', $client->id)
            ->orderBy('sold_at', 'desc')
            ->first();

        $paymentProgress = $totalGiven > 0 ? min(100, round(($totalPaid / $totalGiven) * 100, 1)) : 0;
        
        $totalOrders = ClientSale::where('client_id', $client->id)->count();

        // Payment status badge string
        $paymentStatus = 'Good Standing';
        if ($outstandingBalance > 0 && $paymentProgress < 50) {
            $paymentStatus = 'Payment Due';
        } elseif ($outstandingBalance > 0) {
            $paymentStatus = 'Partial Paid';
        } elseif ($totalGiven > 0 && $outstandingBalance == 0) {
            $paymentStatus = 'Fully Settled';
        }

        return [
            'total_given' => (float) $totalGiven,
            'total_paid' => (float) $totalPaid,
            'outstanding_balance' => (float) $outstandingBalance,
            'current_credit' => (float) $currentCredit,
            'last_payment_amount' => $lastPayment ? (float) $lastPayment->amount : 0.0,
            'last_payment_date' => $lastPayment ? Carbon::parse($lastPayment->payment_date)->format('M d, Y') : 'No payments',
            'payment_progress' => $paymentProgress,
            'payment_status' => $paymentStatus,
            'total_orders' => $totalOrders,
            'last_order_date' => $lastOrder ? Carbon::parse($lastOrder->sold_at)->format('M d, Y') : 'No orders',
        ];
    }

    /**
     * Get detailed product stock breakdown for client.
     */
    public function getClientProductStock(User $client)
    {
        $assignments = ProductAssignment::with('product')
            ->where('client_id', $client->id)
            ->get();

        return $assignments->map(function ($assignment) use ($client) {
            $soldQty = ClientSale::where('client_id', $client->id)
                ->where('product_id', $assignment->product_id)
                ->sum('quantity');

            $remainingQty = max(0, $assignment->quantity - $soldQty);
            $stockValue = $remainingQty * ($assignment->dealer_price ?? $assignment->selling_price);

            $status = 'In Stock';
            if ($remainingQty <= 0) {
                $status = 'Out of Stock';
            } elseif ($remainingQty <= 5) {
                $status = 'Low Stock';
            }

            return (object) [
                'id' => $assignment->id,
                'product_id' => $assignment->product_id,
                'product' => $assignment->product,
                'assigned_qty' => $assignment->quantity,
                'sold_qty' => $soldQty,
                'remaining_qty' => $remainingQty,
                'dealer_price' => (float) ($assignment->dealer_price ?? $assignment->selling_price),
                'selling_price' => (float) $assignment->selling_price,
                'stock_value' => (float) $stockValue,
                'status' => $status,
            ];
        });
    }

    /**
     * Helper to get high level stock status string for client sidebar.
     */
    protected function getClientStockStatus(User $client): string
    {
        $products = $this->getClientProductStock($client);
        if ($products->isEmpty()) {
            return 'No Products';
        }
        if ($products->contains('status', 'Low Stock')) {
            return 'Low Stock Alert';
        }
        if ($products->contains('status', 'Out of Stock')) {
            return 'Out of Stock';
        }
        return 'Sufficient';
    }

    /**
     * Get complete order & quotation history for client.
     */
    public function getClientOrderHistory(User $client)
    {
        $sales = ClientSale::with('product')
            ->where('client_id', $client->id)
            ->orderBy('sold_at', 'desc')
            ->get()
            ->map(function ($sale) {
                return (object) [
                    'type' => 'Sale',
                    'order_number' => $sale->sale_number,
                    'quotation_number' => 'N/A',
                    'date' => $sale->sold_at,
                    'assigned_by' => 'Admin System',
                    'products' => $sale->product->name ?? 'Product',
                    'quantity' => $sale->quantity,
                    'amount' => (float) $sale->total_amount,
                    'status' => 'Completed',
                ];
            });

        $quotations = Quotation::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($q) {
                return (object) [
                    'type' => 'Quotation',
                    'order_number' => 'QTN-' . $q->id,
                    'quotation_number' => $q->quotation_number,
                    'date' => $q->quotation_date,
                    'assigned_by' => $q->prepared_by ?? 'Admin',
                    'products' => $q->items->count() . ' items',
                    'quantity' => $q->items->sum('quantity'),
                    'amount' => (float) $q->grand_total,
                    'status' => ucfirst($q->status),
                ];
            });

        return $sales->concat($quotations)->sortByDesc('date')->values();
    }

    /**
     * Get payment history for client.
     */
    public function getClientPaymentHistory(User $client)
    {
        return Payment::with(['collector', 'reviewer'])
            ->where('client_id', $client->id)
            ->orderBy('payment_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get stock requests for client.
     */
    public function getClientStockRequests(User $client)
    {
        return StockRequest::with(['product', 'reviewer'])
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get notes for client.
     */
    public function getClientNotes(User $client)
    {
        return ClientNote::with('refUser')
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get unified chronological activity timeline for client.
     */
    public function getClientActivityTimeline(User $client)
    {
        $events = collect();

        // 1. Client Registration
        if ($client->created_at) {
            $events->push((object) [
                'type' => 'registered',
                'title' => 'Client Account Registered',
                'description' => "Account created for {$client->name} (" . ($client->business_name ?? 'Business') . ")",
                'timestamp' => $client->created_at,
                'icon' => 'user-add',
                'color' => 'emerald',
            ]);
        }

        // 2. Product Assignments
        ProductAssignment::with('product')
            ->where('client_id', $client->id)
            ->get()
            ->each(function ($pa) use ($events) {
                $events->push((object) [
                    'type' => 'assignment',
                    'title' => 'Product Stock Assigned',
                    'description' => "{$pa->quantity} units of " . ($pa->product->name ?? 'Product') . " assigned (Value: LKR " . number_format((float) $pa->total_dealer_amount, 2) . ")",
                    'timestamp' => $pa->created_at ?? $pa->assigned_at,
                    'icon' => 'cube',
                    'color' => 'blue',
                ]);
            });

        // 3. Sales / Orders
        ClientSale::with('product')
            ->where('client_id', $client->id)
            ->get()
            ->each(function ($sale) use ($events) {
                $events->push((object) [
                    'type' => 'order',
                    'title' => 'Order Completed (' . $sale->sale_number . ')',
                    'description' => "Sold {$sale->quantity} units of " . ($sale->product->name ?? 'Product') . " for LKR " . number_format($sale->total_amount, 2),
                    'timestamp' => Carbon::parse($sale->sold_at),
                    'icon' => 'shopping-cart',
                    'color' => 'purple',
                ]);
            });

        // 4. Payments Recorded & Verifications
        Payment::with('reviewer')
            ->where('client_id', $client->id)
            ->get()
            ->each(function ($payment) use ($events) {
                $events->push((object) [
                    'type' => 'payment_recorded',
                    'title' => 'Payment Recorded (' . $payment->payment_number . ')',
                    'description' => 'Recorded LKR ' . number_format($payment->amount, 2) . ' via ' . ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Bank Transfer')),
                    'timestamp' => Carbon::parse($payment->payment_date),
                    'icon' => 'cash',
                    'color' => 'amber',
                ]);

                if ($payment->status === Payment::STATUS_APPROVED && $payment->reviewed_at) {
                    $events->push((object) [
                        'type' => 'payment_verified',
                        'title' => 'Payment Verified & Approved',
                        'description' => 'Admin verified payment ' . $payment->payment_number . ' for LKR ' . number_format($payment->amount, 2),
                        'timestamp' => $payment->reviewed_at,
                        'icon' => 'badge-check',
                        'color' => 'emerald',
                    ]);
                }
            });

        // 5. Stock Requests
        StockRequest::with('product')
            ->where('client_id', $client->id)
            ->get()
            ->each(function ($sr) use ($events) {
                $events->push((object) [
                    'type' => 'stock_request',
                    'title' => 'Stock Request Submitted (' . $sr->request_number . ')',
                    'description' => "Requested {$sr->requested_quantity} units of " . ($sr->product->name ?? 'Product') . " (Status: " . ucfirst($sr->status) . ")",
                    'timestamp' => $sr->created_at,
                    'icon' => 'truck',
                    'color' => 'indigo',
                ]);
            });

        // 6. Notes
        ClientNote::with('refUser')
            ->where('client_id', $client->id)
            ->get()
            ->each(function ($note) use ($events) {
                $events->push((object) [
                    'type' => 'note',
                    'title' => 'Client Note Added by ' . ($note->refUser->full_name ?? 'Ref'),
                    'description' => $note->content,
                    'timestamp' => $note->created_at,
                    'icon' => 'annotation',
                    'color' => 'teal',
                ]);
            });

        return $events->sortByDesc('timestamp')->values();
    }

    /**
     * Record new payment received from client.
     */
    public function recordClientPayment(User $client, User $refUser, array $data): Payment
    {
        return DB::transaction(function () use ($client, $refUser, $data) {
            $screenshotPath = null;
            if (isset($data['payment_screenshot']) && $data['payment_screenshot']->isValid()) {
                $screenshotPath = $data['payment_screenshot']->store('payments/receipts', 'public');
            }

            $payment = Payment::create([
                'payment_number' => Payment::generatePaymentNumber(),
                'client_id' => $client->id,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'] ?? 'cash',
                'payment_date' => $data['payment_date'] ?? date('Y-m-d'),
                'reference_number' => $data['reference_number'] ?? 'REF-' . strtoupper(substr(uniqid(), -6)),
                'bank_name' => $data['bank_name'] ?? 'N/A',
                'cheque_number' => $data['cheque_number'] ?? null,
                'card_last_four' => $data['card_last_four'] ?? null,
                'payment_screenshot' => $screenshotPath ?? 'payments/placeholder.png',
                'remarks' => $data['remarks'] ?? null,
                'status' => Payment::STATUS_PENDING,
                'collected_by' => $refUser->id,
            ]);

            // Notify all Admins
            $admins = User::where('role', User::ROLE_ADMIN)
                ->orWhereHas('roles', function ($q) {
                    $q->whereIn('name', ['Admin', 'Super Admin']);
                })->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewPaymentRecordedNotification($payment));
            }

            return $payment;
        });
    }

    /**
     * Submit new stock request for client.
     */
    public function submitClientStockRequest(User $client, User $refUser, array $data): StockRequest
    {
        if (! ($client->isClient() || $client->role === User::ROLE_CLIENT || $client->hasRole('Client'))) {
            throw new \InvalidArgumentException('Stock requests can only be submitted for client accounts. Ref and Admin users cannot receive stock requests.');
        }

        return DB::transaction(function () use ($client, $refUser, $data) {
            $attachmentPath = null;
            if (isset($data['attachment']) && $data['attachment']->isValid()) {
                $attachmentPath = $data['attachment']->store('stock_requests/attachments', 'public');
            }

            $stockRequest = StockRequest::create([
                'request_number' => StockRequest::generateRequestNumber(),
                'client_id' => $client->id,
                'product_id' => $data['product_id'],
                'requested_quantity' => $data['requested_quantity'],
                'priority' => $data['priority'] ?? 'medium',
                'expected_delivery_date' => $data['expected_delivery_date'] ?? null,
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
                'attachment_path' => $attachmentPath,
                'status' => StockRequest::STATUS_PENDING,
            ]);

            // Notify all Admins
            $admins = User::where('role', User::ROLE_ADMIN)
                ->orWhereHas('roles', function ($q) {
                    $q->whereIn('name', ['Admin', 'Super Admin']);
                })->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewStockRequestSubmittedNotification($stockRequest));
            }

            return $stockRequest;
        });
    }

    /**
     * Add note for client.
     */
    public function addClientNote(User $client, User $refUser, string $content): ClientNote
    {
        return ClientNote::create([
            'client_id' => $client->id,
            'ref_id' => $refUser->id,
            'content' => $content,
        ]);
    }
}
