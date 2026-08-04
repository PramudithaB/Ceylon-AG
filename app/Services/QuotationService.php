<?php

namespace App\Services;

use App\Models\Quotation;
use App\Repositories\Contracts\QuotationRepositoryInterface;

class QuotationService
{
    protected QuotationRepositoryInterface $repository;
    protected QuotationMailService $mailService;

    public function __construct(
        QuotationRepositoryInterface $repository,
        QuotationMailService $mailService
    ) {
        $this->repository = $repository;
        $this->mailService = $mailService;
    }

    /**
     * Prepare calculated data and create quotation.
     */
    public function createQuotation(array $data, int $userId): Quotation
    {
        $calculatedData = $this->calculateTotals($data);
        $calculatedData['created_by'] = $userId;

        return $this->repository->create($calculatedData);
    }

    /**
     * Prepare calculated data and update existing quotation.
     */
    public function updateQuotation(Quotation $quotation, array $data): Quotation
    {
        $calculatedData = $this->calculateTotals($data);

        return $this->repository->update($quotation, $calculatedData);
    }

    /**
     * Duplicate existing quotation as new Draft.
     */
    public function duplicateQuotation(Quotation $quotation, int $userId): Quotation
    {
        return $this->repository->duplicate($quotation, $userId);
    }

    /**
     * Delete quotation.
     */
    public function deleteQuotation(Quotation $quotation): bool
    {
        return $this->repository->delete($quotation);
    }

    /**
     * Send email with PDF attached to client.
     */
    public function emailQuotation(Quotation $quotation, ?string $email = null): bool
    {
        return $this->mailService->sendQuotationEmail($quotation, $email);
    }

    /**
     * Calculate line item totals, subtotal, discounts, and grand total.
     */
    public function calculateTotals(array $data): array
    {
        $subtotal = 0.00;
        $processedItems = [];

        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $qty = (int) ($item['quantity'] ?? 1);
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $discount = (float) ($item['discount'] ?? 0);

                $lineTotal = max(0, ($qty * $unitPrice) - $discount);
                $subtotal += $lineTotal;

                $processedItems[] = array_merge($item, [
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'total' => $lineTotal,
                ]);
            }
        }

        $overallDiscount = (float) ($data['discount_amount'] ?? 0);
        $taxAmount = (float) ($data['tax_amount'] ?? 0);
        $grandTotal = max(0, ($subtotal - $overallDiscount) + $taxAmount);

        $data['items'] = $processedItems;
        $data['subtotal'] = round($subtotal, 2);
        $data['discount_amount'] = round($overallDiscount, 2);
        $data['tax_amount'] = round($taxAmount, 2);
        $data['grand_total'] = round($grandTotal, 2);

        return $data;
    }
}
