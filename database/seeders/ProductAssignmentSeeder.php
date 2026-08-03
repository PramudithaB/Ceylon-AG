<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::role('Super Admin')->first() ?? User::role('Admin')->first();
        $client = User::role('Client')->first();
        $product = Product::first();

        if ($admin && $client && $product) {
            $qty = 10;
            $dealerPrice = $product->dealer_price;
            $sellingPrice = $product->selling_price;

            ProductAssignment::create([
                'assignment_number' => ProductAssignment::generateAssignmentNumber(),
                'client_id' => $client->id,
                'product_id' => $product->id,
                'assigned_by' => $admin->id,
                'quantity' => $qty,
                'dealer_price' => $dealerPrice,
                'selling_price' => $sellingPrice,
                'total_dealer_amount' => $qty * $dealerPrice,
                'notes' => 'Initial inventory allocation for Q3 regional distribution.',
                'assigned_at' => now(),
            ]);

            // Deduct stock
            $product->decrement('stock_quantity', $qty);
        }
    }
}
