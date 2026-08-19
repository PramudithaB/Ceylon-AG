<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ($this->user()?->isClient() || $this->user()?->hasRole('Client')) ?? false;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'bank_name' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'payment_screenshot' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'], // 5MB limit
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
