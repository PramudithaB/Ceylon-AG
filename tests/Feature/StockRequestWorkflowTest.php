<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\StockRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockRequestWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $ref;
    protected User $clientA;
    protected User $clientB;
    protected Product $productX;
    protected Product $productY;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $category = Category::create([
            'name' => 'Agricultural Chemicals',
            'slug' => 'agri-chemicals',
            'status' => 'active',
        ]);

        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->ref = User::factory()->create([
            'role' => User::ROLE_REF,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->clientA = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->ref->id,
            'email_verified_at' => now(),
        ]);

        $this->clientB = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->ref->id,
            'email_verified_at' => now(),
        ]);

        $this->productX = Product::create([
            'category_id' => $category->id,
            'name' => 'Ceylon Bio Fertilizer 5L',
            'sku' => 'CBF-5L',
            'buying_price' => 1000,
            'dealer_price' => 1500,
            'selling_price' => 2000,
            'stock_quantity' => 100,
            'minimum_stock' => 10,
            'status' => 'active',
        ]);

        $this->productY = Product::create([
            'category_id' => $category->id,
            'name' => 'Crop Protector 1L',
            'sku' => 'CP-1L',
            'buying_price' => 500,
            'dealer_price' => 800,
            'selling_price' => 1200,
            'stock_quantity' => 50,
            'minimum_stock' => 5,
            'status' => 'active',
        ]);
    }

    /**
     * TEST 1: Ref creates request -> Admin approves -> Automatically assigned to Client A.
     */
    public function test_ref_creates_stock_request_and_admin_approval_automatically_assigns_stock(): void
    {
        // 1. Ref creates stock request for Client A
        $this->actingAs($this->ref);

        $response = $this->post(route('ref.stock-requests.store'), [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'notes' => 'Urgent replenishment for seasonal harvest',
        ]);

        $response->assertRedirect(route('ref.stock-requests.index'));

        $this->assertDatabaseHas('stock_requests', [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $stockRequest = StockRequest::first();

        // Verify Client A does not have assigned stock while pending
        $this->actingAs($this->clientA);
        $dashboardBefore = $this->get('/dashboard');
        $dashboardBefore->assertStatus(200);
        $dashboardBefore->assertSee('0 units');

        // 2. Admin approves the stock request
        $this->actingAs($this->admin);

        $approveResponse = $this->patch(route('admin.stock-requests.approve', ['stockRequest' => $stockRequest->id]));
        $approveResponse->assertSessionHas('success');

        // Verify Stock Request is marked approved
        $stockRequest->refresh();
        $this->assertTrue($stockRequest->isApproved());
        $this->assertEquals($this->admin->id, $stockRequest->reviewed_by);

        // Verify warehouse stock is deducted
        $this->productX->refresh();
        $this->assertEquals(90, $this->productX->stock_quantity);

        // Verify ProductAssignment record was automatically created
        $this->assertDatabaseHas('product_assignments', [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'quantity' => 10,
            'dealer_price' => 1500,
            'selling_price' => 2000,
        ]);

        // 3. Client A Dashboard immediately displays the approved stock and request details
        $this->actingAs($this->clientA);
        $dashboardAfter = $this->get('/dashboard');
        $dashboardAfter->assertStatus(200);
        $dashboardAfter->assertSee('10 units');
        $dashboardAfter->assertSee('Ceylon Bio Fertilizer 5L');
        $dashboardAfter->assertSee($stockRequest->request_number);
        $dashboardAfter->assertSee('Approved');
        $dashboardAfter->assertSee('Assigned');
    }

    /**
     * TEST 2: Ref creates request -> Admin rejects -> 0 quantity assigned.
     */
    public function test_admin_rejects_stock_request_no_stock_is_assigned(): void
    {
        $this->actingAs($this->ref);

        $this->post(route('ref.stock-requests.store'), [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
        ]);

        $stockRequest = StockRequest::first();

        // Admin rejects
        $this->actingAs($this->admin);
        $rejectResponse = $this->patch(route('admin.stock-requests.reject', ['stockRequest' => $stockRequest->id]), [
            'rejection_reason' => 'Credit limit exceeded for this quarter',
        ]);
        $rejectResponse->assertSessionHas('success');

        $stockRequest->refresh();
        $this->assertTrue($stockRequest->isRejected());
        $this->assertEquals('Credit limit exceeded for this quarter', $stockRequest->rejection_reason);

        // Verify NO product assignments created
        $this->assertEquals(0, ProductAssignment::where('client_id', $this->clientA->id)->count());

        // Verify warehouse stock unchanged
        $this->productX->refresh();
        $this->assertEquals(100, $this->productX->stock_quantity);

        // Client A sees 0 assigned
        $this->actingAs($this->clientA);
        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('0 units');
    }

    /**
     * TEST 3: Admin approves the same request again -> Prevent duplicate assignment.
     */
    public function test_prevent_duplicate_assignment_on_re_approval(): void
    {
        $stockRequest = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->admin);

        // First approval
        $this->patch(route('admin.stock-requests.approve', ['stockRequest' => $stockRequest->id]));

        $this->assertEquals(1, ProductAssignment::where('client_id', $this->clientA->id)->count());
        $this->assertEquals(90, $this->productX->fresh()->stock_quantity);

        // Second approval attempt
        $secondResponse = $this->patch(route('admin.stock-requests.approve', ['stockRequest' => $stockRequest->id]));
        $secondResponse->assertSessionHas('error');

        // Still only 1 assignment and 90 stock
        $this->assertEquals(1, ProductAssignment::where('client_id', $this->clientA->id)->count());
        $this->assertEquals(90, $this->productX->fresh()->stock_quantity);
    }

    /**
     * TEST 4: Two separate approved requests -> Client sees combined total.
     */
    public function test_two_separate_approved_requests_aggregate_properly(): void
    {
        $req1 = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $req2 = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber() . '-2',
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 5,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->admin);
        $this->patch(route('admin.stock-requests.approve', ['stockRequest' => $req1->id]));
        $this->patch(route('admin.stock-requests.approve', ['stockRequest' => $req2->id]));

        $this->assertEquals(15, ProductAssignment::where('client_id', $this->clientA->id)->sum('quantity'));
        $this->assertEquals(85, $this->productX->fresh()->stock_quantity);

        // Client A dashboard sees 15 units
        $this->actingAs($this->clientA);
        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('15 units');
    }

    /**
     * TEST 5: Manual product assignment continues to work properly.
     */
    public function test_manual_product_assignment_feature_works_alongside_auto_assignment(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.product-assignments.store'), [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productY->id,
            'quantity' => 8,
            'dealer_price' => 800,
            'selling_price' => 1200,
            'notes' => 'Manual dispatch',
        ]);

        $response->assertSessionHas('flash_message');

        $this->assertDatabaseHas('product_assignments', [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productY->id,
            'quantity' => 8,
        ]);

        $this->assertEquals(42, $this->productY->fresh()->stock_quantity);
    }

    /**
     * TEST 6: Client A cannot see Client B's assigned stock.
     */
    public function test_client_isolation_client_a_does_not_see_client_b_stock(): void
    {
        // Assign to Client B
        ProductAssignment::create([
            'assignment_number' => ProductAssignment::generateAssignmentNumber(),
            'client_id' => $this->clientB->id,
            'product_id' => $this->productX->id,
            'assigned_by' => $this->admin->id,
            'quantity' => 25,
            'dealer_price' => 1500,
            'selling_price' => 2000,
            'total_dealer_amount' => 37500,
            'assigned_at' => now(),
        ]);

        $this->actingAs($this->clientA);
        $dashboard = $this->get('/dashboard');
        $dashboard->assertStatus(200);
        $dashboard->assertSee('0 units');
        $dashboard->assertDontSee('25 units');
    }

    /**
     * TEST 7: Ref user cannot approve requests.
     */
    public function test_ref_cannot_approve_stock_requests(): void
    {
        $stockRequest = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->ref);

        $response = $this->patch(route('admin.stock-requests.approve', ['stockRequest' => $stockRequest->id]));
        $response->assertStatus(403);

        $this->assertTrue($stockRequest->fresh()->isPending());
    }

    /**
     * TEST 8: Client user cannot approve requests.
     */
    public function test_client_cannot_approve_stock_requests(): void
    {
        $stockRequest = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->clientA);

        $response = $this->patch(route('admin.stock-requests.approve', ['stockRequest' => $stockRequest->id]));
        $response->assertStatus(403);

        $this->assertTrue($stockRequest->fresh()->isPending());
    }

    /**
     * Warehouse stock validation: When warehouse has insufficient stock, approval fails cleanly.
     */
    public function test_approval_fails_when_warehouse_stock_insufficient(): void
    {
        $stockRequest = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 500, // Product X only has 100
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->admin);

        $response = $this->patch(route('admin.stock-requests.approve', ['stockRequest' => $stockRequest->id]));
        $response->assertSessionHas('error');

        // Request remains pending, 0 assignments created, stock unchanged
        $this->assertTrue($stockRequest->fresh()->isPending());
        $this->assertEquals(0, ProductAssignment::where('client_id', $this->clientA->id)->count());
        $this->assertEquals(100, $this->productX->fresh()->stock_quantity);
    }
}
