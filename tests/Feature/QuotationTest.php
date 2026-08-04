<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class QuotationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $client;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed settings
        CompanySetting::getSettings();

        // Create Spatie roles
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Client']);

        // Create Admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ceylonag.com',
            'email_verified_at' => now()->toDateTimeString(),
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'approved',
        ]);
        $this->admin->assignRole('Admin');

        // Create Client user
        $this->client = User::create([
            'name' => 'Samantha Fernando',
            'email' => 'client@farm.lk',
            'email_verified_at' => now()->toDateTimeString(),
            'password' => bcrypt('password'),
            'role' => 'client',
            'status' => 'approved',
            'business_name' => 'Green Fields Farm',
        ]);
        $this->client->markEmailAsVerified();
        $this->client->assignRole('Client');

        $category = Category::create([
            'name' => 'Fertilizers',
            'slug' => 'fertilizers',
        ]);

        // Create Product
        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Organic Fertilizer 50kg',
            'sku' => 'FERT-001',
            'buying_price' => 4000.00,
            'dealer_price' => 5000.00,
            'selling_price' => 5500.00,
            'stock_quantity' => 100,
            'minimum_stock' => 10,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_access_quotation_dashboard()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.quotations.dashboard'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_a_quotation()
    {
        $data = [
            'client_id' => $this->client->id,
            'customer_name' => 'Samantha Fernando',
            'business_name' => 'Green Fields Farm',
            'phone' => '0771234567',
            'email' => 'client@farm.lk',
            'quotation_date' => date('Y-m-d'),
            'expiry_date' => date('Y-m-d', strtotime('+30 days')),
            'status' => 'draft',
            'discount_amount' => 500.00,
            'tax_amount' => 0.00,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'product_name' => $this->product->name,
                    'description' => '50kg bag',
                    'quantity' => 10,
                    'unit_price' => 5500.00,
                    'discount' => 0.00,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.quotations.store'), $data);

        $quotation = Quotation::first();
        $response->assertRedirect(route('admin.quotations.show', $quotation->id));
        $this->assertDatabaseHas('quotations', [
            'customer_name' => 'Samantha Fernando',
            'subtotal' => 55000.00,
            'discount_amount' => 500.00,
            'grand_total' => 54500.00,
        ]);
    }

    public function test_client_can_view_assigned_quotation_read_only()
    {
        $quotation = Quotation::create([
            'quotation_number' => 'QTN-2026-000001',
            'client_id' => $this->client->id,
            'created_by' => $this->admin->id,
            'customer_name' => 'Samantha Fernando',
            'quotation_date' => date('Y-m-d'),
            'expiry_date' => date('Y-m-d', strtotime('+30 days')),
            'status' => 'sent',
            'subtotal' => 1000.00,
            'grand_total' => 1000.00,
        ]);

        $response = $this->actingAs($this->client)->get(route('client.quotations.show', $quotation->id));
        $response->assertStatus(200);

        // Client cannot access admin create page
        $forbiddenResponse = $this->actingAs($this->client)->get(route('admin.quotations.create'));
        $forbiddenResponse->assertStatus(403);
    }
}
