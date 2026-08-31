<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\StockRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefDashboardAndAssignmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $ref;
    protected User $clientA;
    protected User $clientB;
    protected Product $productX;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $category = Category::create([
            'name' => 'Agro Chemicals',
            'slug' => 'agro-chemicals',
            'status' => 'active',
        ]);

        $this->admin = User::factory()->create([
            'name' => 'System Admin',
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->ref = User::factory()->create([
            'name' => 'John Ref',
            'role' => User::ROLE_REF,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->clientA = User::factory()->create([
            'name' => 'Alice Farmer',
            'business_name' => 'Alice Agri Farms',
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->ref->id,
            'email_verified_at' => now(),
        ]);

        $this->clientB = User::factory()->create([
            'name' => 'Bob Grower',
            'business_name' => 'Bob Plantations',
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->ref->id,
            'email_verified_at' => now(),
        ]);

        $this->productX = Product::create([
            'category_id' => $category->id,
            'name' => 'Ceylon Organic Fertilizer 10kg',
            'sku' => 'COF-10KG',
            'buying_price' => 1200,
            'dealer_price' => 1800,
            'selling_price' => 2400,
            'stock_quantity' => 100,
            'minimum_stock' => 10,
            'status' => 'active',
        ]);
    }

    /**
     * TEST 1: Login as Ref -> Ref Dashboard is simple and focused.
     */
    public function test_1_ref_can_login_and_view_simplified_dashboard(): void
    {
        $response = $this->actingAs($this->ref)->get(route('ref.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Welcome, John Ref');
        $response->assertSee('Select Client');
        $response->assertSee('Create Stock Request');
        $response->assertSee('Recent Stock Requests');
        $response->assertSee('Pending');
        $response->assertSee('Approved');
        $response->assertSee('Rejected');
    }

    /**
     * TEST 2: Ref selects Client A, creates Product X qty 10 -> Stock Request belongs to Client A.
     */
    public function test_2_ref_creates_stock_request_for_client_a(): void
    {
        $this->actingAs($this->ref);

        $response = $this->post(route('ref.stock-requests.store'), [
            'source' => 'dashboard',
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'notes' => 'Seasonal restock order',
        ]);

        $response->assertRedirect(route('ref.dashboard', ['client_id' => $this->clientA->id]));

        $this->assertDatabaseHas('stock_requests', [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);
    }

    /**
     * TEST 3: Ref checks their own account -> No product is assigned to the Ref.
     */
    public function test_3_no_product_is_assigned_to_ref_account(): void
    {
        // Create a pending request
        StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $this->assertEquals(0, ProductAssignment::where('client_id', $this->ref->id)->count());
    }

    /**
     * TEST 4: Admin approves the request -> Product quantity is assigned to Client A (NOT Ref, NOT Admin).
     */
    public function test_4_admin_approves_request_and_product_is_assigned_only_to_client_a(): void
    {
        $stockRequest = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->admin);
        $approveResponse = $this->patch(route('admin.stock-requests.approve', $stockRequest));
        $approveResponse->assertSessionHas('success');

        // Verify assignment for Client A
        $this->assertDatabaseHas('product_assignments', [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'quantity' => 10,
        ]);

        // Verify NO assignment for Ref or Admin
        $this->assertEquals(0, ProductAssignment::where('client_id', $this->ref->id)->count());
        $this->assertEquals(0, ProductAssignment::where('client_id', $this->admin->id)->count());
    }

    /**
     * TEST 5: Client A logs in -> Client A sees the approved/assigned Product X quantity.
     */
    public function test_5_client_a_sees_approved_assigned_quantity_on_dashboard(): void
    {
        ProductAssignment::create([
            'assignment_number' => ProductAssignment::generateAssignmentNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'assigned_by' => $this->admin->id,
            'quantity' => 10,
            'dealer_price' => 1800,
            'selling_price' => 2400,
            'total_dealer_amount' => 18000,
            'assigned_at' => now(),
        ]);

        $this->actingAs($this->clientA);
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('10 units');
        $response->assertSee('Ceylon Organic Fertilizer 10kg');
    }

    /**
     * TEST 6: Client B logs in -> Client B cannot see Client A's assigned product.
     */
    public function test_6_client_b_cannot_see_client_a_assigned_stock(): void
    {
        ProductAssignment::create([
            'assignment_number' => ProductAssignment::generateAssignmentNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'assigned_by' => $this->admin->id,
            'quantity' => 10,
            'dealer_price' => 1800,
            'selling_price' => 2400,
            'total_dealer_amount' => 18000,
            'assigned_at' => now(),
        ]);

        $this->actingAs($this->clientB);
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('0 units');
        $response->assertDontSee('10 units');
    }

    /**
     * TEST 7: Ref attempts to submit a request using their own user ID as recipient -> Backend rejects it.
     */
    public function test_7_backend_rejects_ref_as_stock_request_recipient(): void
    {
        $this->actingAs($this->ref);

        $response = $this->post(route('ref.stock-requests.store'), [
            'client_id' => $this->ref->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 5,
        ]);

        $response->assertSessionHasErrors('client_id');
        $this->assertEquals(0, StockRequest::where('client_id', $this->ref->id)->count());
    }

    /**
     * TEST 8: Ref attempts to assign / request a product directly for an Admin -> Backend rejects it.
     */
    public function test_8_backend_rejects_admin_as_stock_request_recipient(): void
    {
        $this->actingAs($this->ref);

        $response = $this->post(route('ref.stock-requests.store'), [
            'client_id' => $this->admin->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 5,
        ]);

        $response->assertSessionHasErrors('client_id');
        $this->assertEquals(0, StockRequest::where('client_id', $this->admin->id)->count());
    }

    /**
     * TEST 9: Admin attempts to assign a product to themselves or a Ref -> Backend rejects it.
     */
    public function test_9_backend_rejects_admin_self_assignment_and_ref_assignment(): void
    {
        $this->actingAs($this->admin);

        // Attempt assigning to Admin self
        $adminResponse = $this->post(route('admin.product-assignments.store'), [
            'client_id' => $this->admin->id,
            'product_id' => $this->productX->id,
            'quantity' => 5,
            'dealer_price' => 1800,
            'selling_price' => 2400,
        ]);

        $adminResponse->assertSessionHasErrors('client_id');
        $this->assertEquals(0, ProductAssignment::where('client_id', $this->admin->id)->count());

        // Attempt assigning to Ref
        $refResponse = $this->post(route('admin.product-assignments.store'), [
            'client_id' => $this->ref->id,
            'product_id' => $this->productX->id,
            'quantity' => 5,
            'dealer_price' => 1800,
            'selling_price' => 2400,
        ]);

        $refResponse->assertSessionHasErrors('client_id');
        $this->assertEquals(0, ProductAssignment::where('client_id', $this->ref->id)->count());
    }

    /**
     * TEST 10: Same approved request is processed twice -> No duplicate assignment.
     */
    public function test_10_re_approving_same_request_prevents_duplicate_assignment(): void
    {
        $stockRequest = StockRequest::create([
            'request_number' => StockRequest::generateRequestNumber(),
            'client_id' => $this->clientA->id,
            'product_id' => $this->productX->id,
            'requested_quantity' => 10,
            'status' => StockRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->admin);

        // 1st approval
        $this->patch(route('admin.stock-requests.approve', $stockRequest));
        $this->assertEquals(1, ProductAssignment::where('client_id', $this->clientA->id)->count());
        $this->assertEquals(90, $this->productX->fresh()->stock_quantity);

        // 2nd approval attempt
        $secondResponse = $this->patch(route('admin.stock-requests.approve', $stockRequest));
        $secondResponse->assertSessionHas('error');

        // Assert still 1 assignment and 90 stock
        $this->assertEquals(1, ProductAssignment::where('client_id', $this->clientA->id)->count());
        $this->assertEquals(90, $this->productX->fresh()->stock_quantity);
    }
}
