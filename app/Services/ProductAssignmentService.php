<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Mail\ProductAssignedMail;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\User;
use App\Repositories\ProductAssignmentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProductAssignmentService
{
    public function __construct(
        protected ProductAssignmentRepositoryInterface $assignmentRepository
    ) {}

    /**
     * Get paginated assignment records.
     */
    public function getAssignments(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->assignmentRepository->getAllPaginated($filters, $perPage);
    }

    /**
     * Get assignments for a specific client.
     */
    public function getClientAssignments(User $client, int $perPage = 10): LengthAwarePaginator
    {
        return $this->assignmentRepository->getByClient($client, $perPage);
    }

    /**
     * Find assignment by ID.
     */
    public function getAssignment(int|string $id): ?ProductAssignment
    {
        return $this->assignmentRepository->findById($id);
    }

    /**
     * Assign product to client inside DB Transaction.
     * Automatically reduces warehouse stock and sends notification email.
     *
     * @throws InsufficientStockException
     */
    public function assignProduct(array $data, User $assignedBy): ProductAssignment
    {
        return DB::transaction(function () use ($data, $assignedBy) {
            $productId = $data['product_id'];
            $quantity = (int) $data['quantity'];

            // 1. Validate recipient is a Client
            $client = User::findOrFail($data['client_id']);
            if (! ($client->isClient() || $client->role === User::ROLE_CLIENT || $client->hasRole('Client'))) {
                throw new \InvalidArgumentException('Only users with the client role can receive product assignments.');
            }

            // 2. Lock product row for update to prevent race conditions
            $product = Product::lockForUpdate()->findOrFail($productId);

            // 2. Double check warehouse stock availability
            if ($quantity > $product->stock_quantity) {
                throw new InsufficientStockException(
                    "Cannot assign {$quantity} units. Current available stock is {$product->stock_quantity} units."
                );
            }

            // 3. Deduct warehouse stock
            $product->decrement('stock_quantity', $quantity);

            // 4. Calculate total allocation amount
            $dealerPrice = (float) $data['dealer_price'];
            $sellingPrice = (float) $data['selling_price'];
            $totalDealerAmount = $quantity * $dealerPrice;

            // 5. Create Product Assignment record
            $assignment = $this->assignmentRepository->create([
                'assignment_number' => ProductAssignment::generateAssignmentNumber(),
                'client_id' => $data['client_id'],
                'product_id' => $productId,
                'assigned_by' => $assignedBy->id,
                'quantity' => $quantity,
                'dealer_price' => $dealerPrice,
                'selling_price' => $sellingPrice,
                'total_dealer_amount' => $totalDealerAmount,
                'notes' => $data['notes'] ?? null,
                'assigned_at' => now(),
            ]);

            // Load relations for email
            $assignment->load(['client', 'product']);

            // 6. Send queued notification to client
            try {
                $assignment->client->notify(new \App\Notifications\ProductAssignedNotification($assignment));
            } catch (\Exception $e) {
                // Log notification exception if queue/mail fails
                logger()->error('Failed dispatching product assignment notification: ' . $e->getMessage());
            }

            return $assignment;
        });
    }
}
