<?php

namespace Tests\Feature\Ref;

use App\Models\ClientSale;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\StockRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MobileFirstRefExperienceTest extends TestCase
{
    use RefreshDatabase;

    protected User $ref;
    protected User $clientA;
    protected User $clientB;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Ref', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Client', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

        $this->ref = User::factory()->create([
            'name' => 'Kasun Ref',
            'email' => 'kasun.ref@ceylonag.com',
            'role' => User::ROLE_REF,
            'status' => User::STATUS_APPROVED,
        ]);
        $this->ref->assignRole('Ref');

        $this->clientA = User::factory()->create([
            'name' => 'Sunil Perera',
            'business_name' => 'Sunil Agro Center',
            'email' => 'sunil@agro.lk',
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->ref->id,
            'district' => 'Kurunegala',
            'phone' => '0771234567',
        ]);
        $this->clientA->assignRole('Client');

        $this->clientB = User::factory()->create([
            'name' => 'Nimal Silva',
            'business_name' => 'Silva Farm Supplies',
            'email' => 'silva@agro.lk',
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->ref->id,
            'district' => 'Anuradhapura',
            'phone' => '0719876543',
        ]);
        $this->clientB->assignRole('Client');

        $category = \App\Models\Category::firstOrCreate(
            ['slug' => 'organic-fertilizers'],
            ['name' => 'Organic Fertilizers', 'status' => 'active']
        );

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Organic Fertilizer Max',
            'sku' => 'OFM-100',
            'buying_price' => 1800.00,
            'dealer_price' => 2100.00,
            'selling_price' => 2500.00,
            'stock_quantity' => 100,
            'status' => 'active',
        ]);

        Storage::fake('public');
    }

    /** @test */
    public function ref_dashboard_renders_mobile_first_ui_and_bottom_nav(): void
    {
        $response = $this->actingAs($this->ref)->get(route('ref.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Welcome, Kasun Ref');
        $response->assertSee('Select Client');
        $response->assertSee('Quick Actions');
        $response->assertSee('Create Stock Request');
        $response->assertSee('Record Sale');
        $response->assertSee('Collect Payment');
        $response->assertSee('Reports');
        $response->assertSee('Recent Activity');
    }

    /** @test */
    public function ref_can_select_active_client_and_persist_in_session(): void
    {
        // 1. Select Client A
        $response = $this->actingAs($this->ref)
            ->post(route('ref.client.select'), [
                'client_id' => $this->clientA->id,
            ]);

        $response->assertSessionHas('active_client_id', $this->clientA->id);

        // 2. Next dashboard visit shows Client A as active
        $dashResponse = $this->actingAs($this->ref)->get(route('ref.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Sunil Agro Center');
        $dashResponse->assertSee('Active Client');
        $dashResponse->assertSee('Change Client');

        // 3. Switch to Client B
        $this->actingAs($this->ref)
            ->get(route('ref.dashboard', ['client_id' => $this->clientB->id]));

        $this->assertEquals($this->clientB->id, session('active_client_id'));
    }

    /** @test */
    public function ref_can_submit_stock_request_for_active_client(): void
    {
        $response = $this->actingAs($this->ref)
            ->post(route('ref.stock-requests.store'), [
                'source' => 'dashboard',
                'client_id' => $this->clientA->id,
                'product_id' => $this->product->id,
                'requested_quantity' => 20,
                'notes' => 'Urgent for harvest season',
            ]);

        $response->assertRedirect(route('ref.dashboard', ['client_id' => $this->clientA->id]));

        $this->assertDatabaseHas('stock_requests', [
            'client_id' => $this->clientA->id,
            'product_id' => $this->product->id,
            'requested_quantity' => 20,
            'status' => StockRequest::STATUS_PENDING,
        ]);
    }

    /** @test */
    public function ref_can_record_retail_sale_for_client_with_assigned_inventory(): void
    {
        // Give Client A assigned stock of 15 units
        ProductAssignment::create([
            'assignment_number' => 'ASN-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'client_id' => $this->clientA->id,
            'product_id' => $this->product->id,
            'assigned_by' => $this->ref->id,
            'quantity' => 15,
            'dealer_price' => 2100.00,
            'selling_price' => 2500.00,
            'total_dealer_amount' => 31500.00,
            'assigned_at' => now(),
            'notes' => 'Approved batch',
        ]);

        $response = $this->actingAs($this->ref)
            ->post(route('ref.sales.store'), [
                'source' => 'dashboard',
                'client_id' => $this->clientA->id,
                'product_id' => $this->product->id,
                'quantity' => 5,
                'customer_name' => 'Kamal Farmer',
            ]);

        $response->assertRedirect(route('ref.dashboard', ['client_id' => $this->clientA->id]));

        $this->assertDatabaseHas('client_sales', [
            'client_id' => $this->clientA->id,
            'product_id' => $this->product->id,
            'quantity' => 5,
            'customer_name' => 'Kamal Farmer',
            'unit_price' => 2500.00,
            'total_amount' => 12500.00,
        ]);
    }

    /** @test */
    public function ref_cannot_record_sale_exceeding_client_inventory(): void
    {
        // Give Client A only 3 units
        ProductAssignment::create([
            'assignment_number' => 'ASN-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'client_id' => $this->clientA->id,
            'product_id' => $this->product->id,
            'assigned_by' => $this->ref->id,
            'quantity' => 3,
            'dealer_price' => 2100.00,
            'selling_price' => 2500.00,
            'total_dealer_amount' => 6300.00,
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($this->ref)
            ->post(route('ref.sales.store'), [
                'client_id' => $this->clientA->id,
                'product_id' => $this->product->id,
                'quantity' => 10,
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(0, ClientSale::count());
    }

    /** @test */
    public function ref_can_submit_collected_payment_with_slip_photo(): void
    {
        $fakeSlip = UploadedFile::fake()->image('bank_slip.jpg');

        $response = $this->actingAs($this->ref)
            ->post(route('ref.payments.store'), [
                'source' => 'dashboard',
                'client_id' => $this->clientA->id,
                'amount' => 45000.00,
                'payment_method' => 'bank_transfer',
                'reference_number' => 'SLIP-998822',
                'bank_name' => 'BOC Bank',
                'payment_screenshot' => $fakeSlip,
            ]);

        $response->assertRedirect(route('ref.dashboard', ['client_id' => $this->clientA->id]));

        $this->assertDatabaseHas('payments', [
            'client_id' => $this->clientA->id,
            'amount' => 45000.00,
            'payment_method' => 'bank_transfer',
            'reference_number' => 'SLIP-998822',
            'bank_name' => 'BOC Bank',
            'status' => Payment::STATUS_PENDING,
            'collected_by' => $this->ref->id,
        ]);
    }

    /** @test */
    public function ref_can_view_sales_payments_and_reports_pages(): void
    {
        // 1. Sales Index
        $salesRes = $this->actingAs($this->ref)->get(route('ref.sales.index'));
        $salesRes->assertStatus(200);
        $salesRes->assertSee('Client Sales History');

        // 2. Sales Reports
        $reportsRes = $this->actingAs($this->ref)->get(route('ref.sales.reports'));
        $reportsRes->assertStatus(200);
        $reportsRes->assertSee('Sales Reports');

        // 3. Payments Index
        $payRes = $this->actingAs($this->ref)->get(route('ref.payments.index'));
        $payRes->assertStatus(200);
        $payRes->assertSee('Payments');

        // 4. Stock Requests Index
        $stockRes = $this->actingAs($this->ref)->get(route('ref.stock-requests.index'));
        $stockRes->assertStatus(200);
        $stockRes->assertSee('Stock Requests Log');
    }

    /** @test */
    public function sales_and_stock_requests_reject_non_client_roles(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'status' => User::STATUS_APPROVED]);

        // 1. Sales reject admin recipient
        $saleRes = $this->actingAs($this->ref)->post(route('ref.sales.store'), [
            'client_id' => $admin->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);
        $saleRes->assertSessionHasErrors('client_id');

        // 2. Stock Request rejects ref self-recipient
        $stockRes = $this->actingAs($this->ref)->post(route('ref.stock-requests.store'), [
            'client_id' => $this->ref->id,
            'product_id' => $this->product->id,
            'requested_quantity' => 5,
        ]);
        $stockRes->assertSessionHasErrors('client_id');
    }
}
