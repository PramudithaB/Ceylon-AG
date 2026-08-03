<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreProductAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['Super Admin', 'Admin']) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:users,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'dealer_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom validation to prevent assigning more than available stock.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $productId = $this->input('product_id');
            $requestedQty = (int) $this->input('quantity');

            if ($productId && $requestedQty > 0) {
                $product = Product::find($productId);

                if (! $product) {
                    $validator->errors()->add('product_id', 'Selected product does not exist.');
                    return;
                }

                if ($requestedQty > $product->stock_quantity) {
                    $validator->errors()->add(
                        'quantity',
                        "Cannot assign {$requestedQty} units. Only {$product->stock_quantity} units available in warehouse stock."
                    );
                }
            }

            // Ensure selected client is approved
            $clientId = $this->input('client_id');
            if ($clientId) {
                $client = User::find($clientId);
                if (! $client || ! $client->isApproved()) {
                    $validator->errors()->add('client_id', 'Product can only be assigned to approved clients.');
                }
            }
        });
    }
}
