<?php

namespace App\Http\Requests\Quotation;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['nullable', 'exists:users,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'district' => ['nullable', 'string', 'max:100'],
            'quotation_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date', 'after_or_equal:quotation_date'],
            'status' => ['required', 'in:draft,sent,accepted,rejected,expired'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],

            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'swift_code' => ['nullable', 'string', 'max:100'],

            'notes' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
            'delivery_period' => ['nullable', 'string', 'max:255'],
            'prepared_by' => ['nullable', 'string', 'max:255'],
            'approved_by' => ['nullable', 'string', 'max:255'],

            // Dynamic items array validation
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['required', 'numeric', 'gt:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Customer contact name is required.',
            'items.required' => 'You must add at least one product row.',
            'items.min' => 'At least one product row is required.',
            'items.*.product_name.required' => 'Product name is required for all rows.',
            'items.*.quantity.required' => 'Quantity is required for all rows.',
            'items.*.quantity.gt' => 'Quantity must be greater than zero.',
            'items.*.unit_price.required' => 'Unit price is required for all rows.',
            'items.*.unit_price.gt' => 'Unit price must be greater than zero.',
        ];
    }
}
