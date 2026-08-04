<?php

namespace App\Repositories\Eloquent;

use App\Models\CompanySetting;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Repositories\Contracts\QuotationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuotationRepository implements QuotationRepositoryInterface
{
    /**
     * Get paginated quotations with optional search & filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Quotation::with(['client', 'creator', 'items']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('quotation_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('quotation_date', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Find quotation by ID.
     */
    public function findById(int $id): ?Quotation
    {
        return Quotation::with(['client', 'creator', 'items.product'])->find($id);
    }

    /**
     * Find quotation by quotation number.
     */
    public function findByNumber(string $number): ?Quotation
    {
        return Quotation::with(['client', 'creator', 'items.product'])->where('quotation_number', $number)->first();
    }

    /**
     * Create a new quotation with line items using Database Transaction.
     */
    public function create(array $data): Quotation
    {
        return DB::transaction(function () use ($data) {
            $settings = CompanySetting::getSettings();

            $quotation = Quotation::create([
                'quotation_number' => Quotation::generateQuotationNumber(),
                'client_id' => $data['client_id'] ?? null,
                'created_by' => $data['created_by'],
                'customer_name' => $data['customer_name'],
                'business_name' => $data['business_name'] ?? null,
                'address' => $data['address'] ?? null,
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'district' => $data['district'] ?? null,
                'quotation_date' => $data['quotation_date'],
                'expiry_date' => $data['expiry_date'],
                'status' => $data['status'] ?? Quotation::STATUS_DRAFT,
                'subtotal' => $data['subtotal'] ?? 0.00,
                'discount_amount' => $data['discount_amount'] ?? 0.00,
                'tax_amount' => $data['tax_amount'] ?? 0.00,
                'grand_total' => $data['grand_total'] ?? 0.00,
                'bank_name' => $data['bank_name'] ?? $settings->bank_name,
                'bank_branch' => $data['bank_branch'] ?? $settings->branch,
                'account_name' => $data['account_name'] ?? $settings->account_name,
                'account_number' => $data['account_number'] ?? $settings->account_number,
                'swift_code' => $data['swift_code'] ?? $settings->swift_code,
                'notes' => $data['notes'] ?? null,
                'terms_conditions' => $data['terms_conditions'] ?? $settings->default_terms,
                'delivery_period' => $data['delivery_period'] ?? $settings->default_delivery_period,
                'prepared_by' => $data['prepared_by'] ?? null,
                'approved_by' => $data['approved_by'] ?? null,
            ]);

            if (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $quotation->items()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'description' => $item['description'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount' => $item['discount'] ?? 0.00,
                        'total' => $item['total'],
                    ]);
                }
            }

            return $quotation->fresh(['items', 'client']);
        });
    }

    /**
     * Update existing quotation and line items using Database Transaction.
     */
    public function update(Quotation $quotation, array $data): Quotation
    {
        return DB::transaction(function () use ($quotation, $data) {
            $quotation->update([
                'client_id' => $data['client_id'] ?? $quotation->client_id,
                'customer_name' => $data['customer_name'] ?? $quotation->customer_name,
                'business_name' => $data['business_name'] ?? $quotation->business_name,
                'address' => $data['address'] ?? $quotation->address,
                'phone' => $data['phone'] ?? $quotation->phone,
                'email' => $data['email'] ?? $quotation->email,
                'district' => $data['district'] ?? $quotation->district,
                'quotation_date' => $data['quotation_date'] ?? $quotation->quotation_date,
                'expiry_date' => $data['expiry_date'] ?? $quotation->expiry_date,
                'status' => $data['status'] ?? $quotation->status,
                'subtotal' => $data['subtotal'] ?? $quotation->subtotal,
                'discount_amount' => $data['discount_amount'] ?? $quotation->discount_amount,
                'tax_amount' => $data['tax_amount'] ?? $quotation->tax_amount,
                'grand_total' => $data['grand_total'] ?? $quotation->grand_total,
                'bank_name' => $data['bank_name'] ?? $quotation->bank_name,
                'bank_branch' => $data['bank_branch'] ?? $quotation->bank_branch,
                'account_name' => $data['account_name'] ?? $quotation->account_name,
                'account_number' => $data['account_number'] ?? $quotation->account_number,
                'swift_code' => $data['swift_code'] ?? $quotation->swift_code,
                'notes' => $data['notes'] ?? $quotation->notes,
                'terms_conditions' => $data['terms_conditions'] ?? $quotation->terms_conditions,
                'delivery_period' => $data['delivery_period'] ?? $quotation->delivery_period,
                'prepared_by' => $data['prepared_by'] ?? $quotation->prepared_by,
                'approved_by' => $data['approved_by'] ?? $quotation->approved_by,
            ]);

            if (isset($data['items']) && is_array($data['items'])) {
                $quotation->items()->delete();
                foreach ($data['items'] as $item) {
                    $quotation->items()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'description' => $item['description'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'discount' => $item['discount'] ?? 0.00,
                        'total' => $item['total'],
                    ]);
                }
            }

            return $quotation->fresh(['items', 'client']);
        });
    }

    /**
     * Delete quotation.
     */
    public function delete(Quotation $quotation): bool
    {
        return DB::transaction(function () use ($quotation) {
            $quotation->items()->delete();
            return $quotation->delete();
        });
    }

    /**
     * Duplicate quotation as a new Draft with auto-generated QTN number.
     */
    public function duplicate(Quotation $quotation, int $userId): Quotation
    {
        return DB::transaction(function () use ($quotation, $userId) {
            $newQuotation = $quotation->replicate([
                'quotation_number',
                'created_at',
                'updated_at',
                'sent_at',
            ]);

            $newQuotation->quotation_number = Quotation::generateQuotationNumber();
            $newQuotation->created_by = $userId;
            $newQuotation->status = Quotation::STATUS_DRAFT;
            $newQuotation->quotation_date = now();
            $newQuotation->expiry_date = now()->addDays(30);
            $newQuotation->save();

            foreach ($quotation->items as $item) {
                $newItem = $item->replicate(['quotation_id', 'created_at', 'updated_at']);
                $newItem->quotation_id = $newQuotation->id;
                $newItem->save();
            }

            return $newQuotation->fresh(['items', 'client']);
        });
    }

    /**
     * Get counts for dashboard cards.
     */
    public function getDashboardCounts(): array
    {
        // Automatically check & update expired quotations
        Quotation::where('status', '!=', Quotation::STATUS_ACCEPTED)
            ->where('status', '!=', Quotation::STATUS_EXPIRED)
            ->whereDate('expiry_date', '<', now()->toDateString())
            ->update(['status' => Quotation::STATUS_EXPIRED]);

        return [
            'total' => Quotation::count(),
            'draft' => Quotation::where('status', Quotation::STATUS_DRAFT)->count(),
            'sent' => Quotation::where('status', Quotation::STATUS_SENT)->count(),
            'accepted' => Quotation::where('status', Quotation::STATUS_ACCEPTED)->count(),
            'rejected' => Quotation::where('status', Quotation::STATUS_REJECTED)->count(),
            'expired' => Quotation::where('status', Quotation::STATUS_EXPIRED)->count(),
        ];
    }

    /**
     * Get quotations for a specific client.
     */
    public function getClientQuotations(int $clientId, int $perPage = 10): LengthAwarePaginator
    {
        return Quotation::where('client_id', $clientId)
            ->with('items')
            ->latest()
            ->paginate($perPage);
    }
}
