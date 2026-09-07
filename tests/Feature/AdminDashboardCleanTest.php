<?php

namespace Tests\Feature;

use App\Models\ClientSale;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\StockRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardCleanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_real_database_metrics_and_clean_empty_states(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Assert empty state strings appear instead of fake numbers
        $response->assertSee('No sales records yet');
        $response->assertSee('No stock requests found');
        $response->assertSee('No payments found');
        $response->assertSee('No quotations generated yet');

        // Assert old fake data does NOT appear
        $response->assertDontSee('1,248,500.50');
        $response->assertDontSee('1248500.50');
        $response->assertDontSee('+14.2% from last month');
        $response->assertDontSee('Laravel Core');
        $response->assertDontSee('Architecture Testing Console');
        $response->assertDontSee('Trigger Success Alert');
    }

    public function test_admin_dashboard_shows_live_data_when_records_exist(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $client = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
            'business_name' => 'Green Valley Agro',
        ]);

        $category = \App\Models\Category::create([
            'name' => 'Eco Protection',
            'slug' => 'eco-protection',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Ceylon AG PESTO 200ml',
            'sku' => 'CAM-TEST-01',
            'selling_price' => 1890.00,
            'dealer_price' => 1500.00,
            'buying_price' => 1200.00,
            'stock_quantity' => 100,
            'status' => 'active',
        ]);

        ClientSale::create([
            'sale_number' => 'SL-TEST-001',
            'client_id' => $client->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 1890.00,
            'total_amount' => 9450.00,
            'customer_name' => 'Retail Buyer',
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('SL-TEST-001');
        $response->assertSee('Green Valley Agro');
        $response->assertSee('1 recorded sales');
        $response->assertDontSee('No sales records yet');
    }
}
