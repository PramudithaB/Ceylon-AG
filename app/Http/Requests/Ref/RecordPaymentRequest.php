<?php

namespace App\Http\Requests\Ref;

use Illuminate\Foundation\Http\FormRequest;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isRef() || auth()->user()->hasRole('Ref'));
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', 'in:cash,bank_transfer,online_transfer,credit_card,debit_card,cheque'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['required_if:payment_method,bank_transfer,online_transfer,cheque', 'nullable', 'string', 'max:255'],
            'cheque_number' => ['required_if:payment_method,cheque', 'nullable', 'string', 'max:255'],
            'card_last_four' => ['nullable', 'string', 'size:4'],
            'payment_screenshot' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Payment amount must be greater than zero.',
            'bank_name.required_if' => 'Bank name is required for bank transfer, online transfer, or cheque payments.',
            'cheque_number.required_if' => 'Cheque number is required for cheque payments.',
        ];
    }
}
