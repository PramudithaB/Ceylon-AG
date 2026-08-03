<?php

namespace Database\Seeders;

use App\Models\ClientSale;
use App\Models\ProductAssignment;
use Illuminate\Database\Seeder;

class ClientSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assignment = ProductAssignment::first();

        if ($assignment) {
            $client = $assignment->client;
            $product = $assignment->product;

            if ($client && $product) {
                ClientSale::create([
                    'sale_number' => ClientSale::generateSaleNumber(),
                    'client_id' => $client->id,
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'unit_price' => $product->selling_price,
                    'total_amount' => 3 * $product->selling_price,
                    'customer_name' => 'Perera Agricultural Farm',
                    'notes' => 'Bulk retail fertilizer sale for rice field cultivation.',
                    'sold_at' => now()->subDay(),
                ]);
            }
        }
    }
}
