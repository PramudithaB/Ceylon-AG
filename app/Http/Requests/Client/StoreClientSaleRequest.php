<?php

namespace App\Http\Requests\Client;

use App\Models\ClientSale;
use App\Models\ProductAssignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreClientSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Client') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'sold_at' => ['required', 'date', 'before_or_equal:today'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom validation to enforce client remaining stock limits.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $productId = $this->input('product_id');
            $requestedQty = (int) $this->input('quantity');

            if ($user && $productId && $requestedQty > 0) {
                // Total units assigned by admin to this client
                $totalAssigned = ProductAssignment::where('client_id', $user->id)
                    ->where('product_id', $productId)
                    ->sum('quantity');

                if ($totalAssigned <= 0) {
                    $validator->errors()->add('product_id', 'This product has not been assigned to your business account.');
                    return;
                }

                // Total units already recorded as sold by this client
                $totalSold = ClientSale::where('client_id', $user->id)
                    ->where('product_id', $productId)
                    ->sum('quantity');

                $remainingStock = $totalAssigned - $totalSold;

                if ($requestedQty > $remainingStock) {
                    $validator->errors()->add(
                        'quantity',
                        "Cannot record sale of {$requestedQty} units. You only have {$remainingStock} assigned units remaining in your inventory."
                    );
                }
            }
        });
    }
}
