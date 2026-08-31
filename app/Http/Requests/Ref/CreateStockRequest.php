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
            'client_id' => ['nullable', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'requested_quantity' => ['required', 'integer', 'min:1'],
            'priority' => ['nullable', 'string', 'in:low,medium,high'],
            'expected_delivery_date' => ['nullable', 'date', 'after_or_equal:today'],
            'reason' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf,doc,docx', 'max:5120'],
        ];
    }

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function ($validator) {
            $clientId = $this->input('client_id') ?? ($this->route('client') instanceof \App\Models\User ? $this->route('client')->id : $this->route('client'));
            if ($clientId) {
                $client = \App\Models\User::find($clientId);
                if (! $client || ! ($client->isClient() || $client->role === \App\Models\User::ROLE_CLIENT || $client->hasRole('Client'))) {
                    $validator->errors()->add('client_id', 'Stock requests can only be created for client accounts. Ref and Admin accounts cannot receive product requests.');
                }
            }
        });
    }
}
