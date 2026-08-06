<?php

namespace App\Http\Requests\Ref;

use Illuminate\Foundation\Http\FormRequest;

class CreateStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isRef() || auth()->user()->hasRole('Ref'));
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'requested_quantity' => ['required', 'integer', 'min:1'],
            'priority' => ['nullable', 'string', 'in:low,medium,high'],
            'expected_delivery_date' => ['nullable', 'date', 'after_or_equal:today'],
            'reason' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf,doc,docx', 'max:5120'],
        ];
    }
}
