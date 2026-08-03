<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fertilizers = Category::create([
            'name' => 'Organic & Granular Fertilizers',
            'slug' => 'fertilizers',
            'description' => 'Soil nutrients, NPK compounds, and organic compost for high crop yield.',
            'is_active' => true,
        ]);

        $seeds = Category::create([
            'name' => 'Hybrid Crop Seeds',
            'slug' => 'seeds',
            'description' => 'High-germination paddy, vegetable, and fruit seeds.',
            'is_active' => true,
        ]);

        $equipment = Category::create([
            'name' => 'Farming Tools & Machinery',
            'slug' => 'machinery',
            'description' => 'Sprayers, micro-irrigation kits, and harvesting tools.',
            'is_active' => true,
        ]);

        $protection = Category::create([
            'name' => 'Crop Protection & Care',
            'slug' => 'crop-protection',
            'description' => 'Eco-friendly pest deterrents and crop health boosters.',
            'is_active' => true,
        ]);

        // Products
        Product::create([
            'category_id' => $fertilizers->id,
            'name' => 'Ceylon Super NPK 15-15-15 (50kg Bag)',
            'sku' => 'CAG-FERT-NPK50',
            'description' => 'Balanced compound fertilizer suitable for tea, coconut, and paddy crops.',
            'buying_price' => 7500.00,
            'dealer_price' => 8800.00,
            'selling_price' => 9800.00,
            'stock_quantity' => 120,
            'minimum_stock' => 15,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $fertilizers->id,
            'name' => 'Bio-Enriched Organic Compost (25kg)',
            'sku' => 'CAG-FERT-BIO25',
            'description' => '100% natural compost enriched with micro-nutrients for organic farming.',
            'buying_price' => 1200.00,
            'dealer_price' => 1500.00,
            'selling_price' => 1800.00,
            'stock_quantity' => 4,
            'minimum_stock' => 10, // Low stock alert
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $seeds->id,
            'name' => 'Bg 352 Hybrid Rice Seeds (10kg Pack)',
            'sku' => 'CAG-SEED-RICE352',
            'description' => 'High-yielding 3.5 month paddy seed strain certified by Department of Agriculture.',
            'buying_price' => 2800.00,
            'dealer_price' => 3300.00,
            'selling_price' => 3800.00,
            'stock_quantity' => 45,
            'minimum_stock' => 10,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $equipment->id,
            'name' => '16L Backpack Knapsack Battery Sprayer',
            'sku' => 'CAG-EQP-SPRAY16',
            'description' => 'Rechargeable 12V battery-powered agricultural sprayer with dual nozzle attachments.',
            'buying_price' => 12500.00,
            'dealer_price' => 15000.00,
            'selling_price' => 17500.00,
            'stock_quantity' => 0, // Out of stock
            'minimum_stock' => 5,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $protection->id,
            'name' => 'Neem Oil Botanical Bio-Pesticide (1L)',
            'sku' => 'CAG-PROT-NEEM01',
            'description' => 'Natural pest repellent effective against whiteflies, aphids, and mites.',
            'buying_price' => 2200.00,
            'dealer_price' => 2600.00,
            'selling_price' => 3100.00,
            'stock_quantity' => 28,
            'minimum_stock' => 5,
            'status' => 'active',
        ]);
    }
}
