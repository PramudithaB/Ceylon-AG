<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefNewWorkflowAndPestoHomeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $ref;
    protected User $otherRef;
    protected User $clientA;
    protected Product $productPesto;
    protected Product $productNPK;

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

        $this->otherRef = User::factory()->create([
            'name' => 'Other Ref',
            'role' => User::ROLE_REF,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->clientA = User::factory()->create([
            'name' => 'Alice Client',
            'business_name' => 'Alice Agro Care',
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->ref->id,
            'email_verified_at' => now(),
        ]);

        $this->productPesto = Product::create([
            'category_id' => $category->id,
            'name' => 'Ceylon AG PESTO',
            'sku' => 'CAM-01',
            'buying_price' => 1200,
            'dealer_price' => 1500,
            'selling_price' => 1890,
            'stock_quantity' => 100,
            'minimum_stock' => 10,
            'status' => 'active',
        ]);

        $this->productNPK = Product::create([
            'category_id' => $category->id,
            'name' => 'Ceylon Super NPK 50KG',
            'sku' => 'CSN-50KG',
            'buying_price' => 4000,
            'dealer_price' => 4800,
            'selling_price' => 5500,
            'stock_quantity' => 50,
            'minimum_stock' => 5,
            'status' => 'active',
        ]);
    }

    /** @test */
    public function test_ref_can_view_client_registration_form(): void
    {
        $response = $this->actingAs($this->ref)->get(route('ref.clients.create'));
        $response->assertStatus(200);
        $response->assertSee('Register Client');
        $response->assertSee('Province');
        $response->assertSee('District');
    }

    /** @test */
    public function test_ref_can_register_new_client_with_automatic_ref_id_and_client_role(): void
    {
        $response = $this->actingAs($this->ref)->post(route('ref.clients.store'), [
            'first_name' => 'Kamal',
            'last_name' => 'Perera',
            'email' => 'kamal@example.com',
            'phone' => '0771234567',
            'business_name' => 'Kamal Grocery',
            'province' => 'Western',
            'district' => 'Gampaha',
            'address' => '123 Main Street',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $client = User::where('email', 'kamal@example.com')->first();
        $this->assertNotNull($client);
        $response->assertRedirect(route('ref.dashboard', ['client_id' => $client->id]));

        $this->assertDatabaseHas('users', [
            'email' => 'kamal@example.com',
            'name' => 'Kamal Perera',
            'role' => User::ROLE_CLIENT,
            'ref_id' => $this->ref->id,
            'province' => 'Western',
            'district' => 'Gampaha',
        ]);
    }

    /** @test */
    public function test_ref_cannot_register_client_with_mismatched_province_and_district(): void
    {
        $response = $this->actingAs($this->ref)->post(route('ref.clients.store'), [
            'first_name' => 'Invalid',
            'last_name' => 'Pair Client',
            'email' => 'invalid@example.com',
            'phone' => '0779998888',
            'business_name' => 'Invalid Pair Agro',
            'province' => 'Western',
            'district' => 'Kandy', // Kandy belongs to Central province, not Western
            'address' => '456 Peradeniya Rd',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('district');

        $this->assertDatabaseMissing('users', [
            'email' => 'invalid@example.com',
        ]);
    }

    /** @test */
    public function test_auth_registration_validates_province_district_match(): void
    {
        // 1. Invalid combination fails
        $invalidResponse = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'business_name' => 'Doe Agro',
            'nic' => '199512345678',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'province' => 'Southern',
            'district' => 'Jaffna', // Jaffna belongs to Northern province
            'phone' => '0712345678',
            'address' => 'Beach Road',
        ]);

        $invalidResponse->assertSessionHasErrors('district');

        // 2. Valid combination passes
        $validResponse = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'business_name' => 'Doe Agro',
            'nic' => '199512345679',
            'email' => 'validuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'province' => 'Southern',
            'district' => 'Galle',
            'phone' => '0712345679',
            'address' => 'Galle Fort',
        ]);

        $validResponse->assertRedirect(route('register.success'));
        $this->assertDatabaseHas('users', [
            'email' => 'validuser@example.com',
            'province' => 'Southern',
            'district' => 'Galle',
        ]);
    }

    /** @test */
    public function test_ref_can_assign_product_to_client(): void
    {
        $response = $this->actingAs($this->ref)->post(route('ref.product-assignments.store'), [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productPesto->id,
            'quantity' => 10,
            'notes' => 'Direct field allocation',
        ]);

        $response->assertRedirect(route('ref.dashboard', ['client_id' => $this->clientA->id]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_assignments', [
            'client_id' => $this->clientA->id,
            'product_id' => $this->productPesto->id,
            'quantity' => 10,
        ]);

        $this->assertEquals(90, $this->productPesto->fresh()->stock_quantity);
    }

    /** @test */
    public function test_ref_cannot_assign_product_to_ref_or_admin_or_self(): void
    {
        // Attempt 1: Assign to another Ref
        $response1 = $this->actingAs($this->ref)->post(route('ref.product-assignments.store'), [
            'client_id' => $this->otherRef->id,
            'product_id' => $this->productPesto->id,
            'quantity' => 5,
        ]);
        $response1->assertSessionHasErrors('client_id');

        // Attempt 2: Assign to Admin
        $response2 = $this->actingAs($this->ref)->post(route('ref.product-assignments.store'), [
            'client_id' => $this->admin->id,
            'product_id' => $this->productPesto->id,
            'quantity' => 5,
        ]);
        $response2->assertSessionHasErrors('client_id');

        // Attempt 3: Assign to self
        $response3 = $this->actingAs($this->ref)->post(route('ref.product-assignments.store'), [
            'client_id' => $this->ref->id,
            'product_id' => $this->productPesto->id,
            'quantity' => 5,
        ]);
        $response3->assertSessionHasErrors('client_id');

        // Verify stock remains untouched
        $this->assertEquals(100, $this->productPesto->fresh()->stock_quantity);
    }

    /** @test */
    public function test_home_page_displays_pesto_and_excludes_unrelated_products(): void
    {
        // Verify official logo file deployment
        $this->assertFileExists(public_path('images/logo.png'));
        $this->assertEquals('aaf6ee366f5a317c9e3e05e5f0f16c3b', md5_file(public_path('images/logo.png')));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ceylon AG PESTO');
        $response->assertSee('CAM-01');
        $response->assertDontSee('1,890.00'); // retail prices must NOT be displayed on corporate homepage
        $response->assertSee('Why Choose PESTO?');
        $response->assertSee('Why Sell PESTO?');
        $response->assertSee('images/logo.png');
        $response->assertDontSee('Ceylon Super NPK 50KG');
        $response->assertDontSee('1,500.00'); // wholesale/dealer price must NOT be exposed
        $response->assertDontSee('100 in stock'); // internal stock counts must NOT be exposed
    }

    /** @test */
    public function test_admin_can_assign_client_to_ref_and_client_appears_in_ref_portfolio(): void
    {
        // 1. Create an unassigned client
        $unassignedClient = User::factory()->create([
            'name' => 'Gamini Silva',
            'business_name' => 'Gamini Green Agro',
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => null,
            'email_verified_at' => now(),
        ]);

        $this->assertNull($unassignedClient->ref_id);
        $this->assertFalse($this->ref->assignedClients->contains($unassignedClient));

        // 2. Admin assigns this client to $this->ref
        $response = $this->actingAs($this->admin)->post(route('admin.clients.assign-ref'), [
            'client_id' => $unassignedClient->id,
            'ref_id' => $this->ref->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $unassignedClient->id,
            'ref_id' => $this->ref->id,
        ]);

        // 3. Client appears under Ref's assignedClients
        $this->assertTrue($this->ref->fresh()->assignedClients->contains($unassignedClient));

        // 4. Ref can log in, view dashboard, and work with this newly assigned client
        $dashResponse = $this->actingAs($this->ref)->get(route('ref.dashboard', ['client_id' => $unassignedClient->id]));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Gamini Green Agro');

        // 5. Ref can submit a stock request for this newly assigned client
        $requestResponse = $this->actingAs($this->ref)->post(route('ref.stock-requests.store'), [
            'client_id' => $unassignedClient->id,
            'product_id' => $this->productPesto->id,
            'requested_quantity' => 12,
            'notes' => 'Seasonal restock for newly assigned client',
        ]);
        $requestResponse->assertRedirect();

        $this->assertDatabaseHas('stock_requests', [
            'client_id' => $unassignedClient->id,
            'product_id' => $this->productPesto->id,
            'requested_quantity' => 12,
        ]);
    }

    /** @test */
    public function test_admin_can_reassign_and_unassign_client_ref(): void
    {
        $client = User::factory()->create([
            'name' => 'Saman Bandara',
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->ref->id,
        ]);

        // Reassign to other Ref
        $reassignResponse = $this->actingAs($this->admin)->post(route('admin.clients.assign-ref'), [
            'client_id' => $client->id,
            'ref_id' => $this->otherRef->id,
        ]);
        $reassignResponse->assertRedirect();
        $this->assertEquals($this->otherRef->id, $client->fresh()->ref_id);

        // Unassign (ref_id = null)
        $unassignResponse = $this->actingAs($this->admin)->post(route('admin.clients.assign-ref'), [
            'client_id' => $client->id,
            'ref_id' => null,
        ]);
        $unassignResponse->assertRedirect();
        $this->assertNull($client->fresh()->ref_id);
    }

    /** @test */
    public function test_admin_assignment_strictly_validates_roles(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        // Attempt 1: Try to assign an Admin user as the Client
        $res1 = $this->actingAs($this->admin)->post(route('admin.clients.assign-ref'), [
            'client_id' => $this->admin->id,
            'ref_id' => $this->ref->id,
        ]);
        $res1->assertSessionHasErrors('client_id');

        // Attempt 2: Try to assign a Ref user as the Client
        $res2 = $this->actingAs($this->admin)->post(route('admin.clients.assign-ref'), [
            'client_id' => $this->otherRef->id,
            'ref_id' => $this->ref->id,
        ]);
        $res2->assertSessionHasErrors('client_id');

        // Attempt 3: Try to select a Client user as the Ref
        $res3 = $this->actingAs($this->admin)->post(route('admin.clients.assign-ref'), [
            'client_id' => $client->id,
            'ref_id' => $this->clientA->id,
        ]);
        $res3->assertSessionHasErrors('ref_id');

        // Attempt 4: Try to select an Admin user as the Ref
        $res4 = $this->actingAs($this->admin)->post(route('admin.clients.assign-ref'), [
            'client_id' => $client->id,
            'ref_id' => $this->admin->id,
        ]);
        $res4->assertSessionHasErrors('ref_id');
    }

    /** @test */
    public function test_non_admins_cannot_assign_clients_to_refs(): void
    {
        $client = User::factory()->create(['role' => User::ROLE_CLIENT]);

        // Ref cannot assign
        $refResponse = $this->actingAs($this->ref)->post(route('admin.clients.assign-ref'), [
            'client_id' => $client->id,
            'ref_id' => $this->ref->id,
        ]);
        $refResponse->assertStatus(403);

        // Client cannot assign
        $clientResponse = $this->actingAs($this->clientA)->post(route('admin.clients.assign-ref'), [
            'client_id' => $client->id,
            'ref_id' => $this->ref->id,
        ]);
        $clientResponse->assertStatus(403);
    }

    /** @test */
    public function test_ref_registered_client_is_immediately_active_and_usable(): void
    {
        $regResponse = $this->actingAs($this->ref)->post(route('ref.clients.store'), [
            'first_name' => 'Nuwan',
            'last_name' => 'Kulasekara',
            'email' => 'nuwan@agrifresh.lk',
            'phone' => '0778889999',
            'business_name' => 'Nuwan Seed Mart',
            'province' => 'North Western',
            'district' => 'Kurunegala',
            'address' => '45 Puttalam Road, Kurunegala',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $newClient = User::where('email', 'nuwan@agrifresh.lk')->first();
        $this->assertNotNull($newClient);
        $this->assertEquals(User::ROLE_CLIENT, $newClient->role);
        $this->assertEquals($this->ref->id, $newClient->ref_id);
        $this->assertEquals(User::STATUS_APPROVED, $newClient->status);
        $this->assertNotNull($newClient->email_verified_at);

        // Verify client can immediately authenticate and access Client portal
        $clientLoginResponse = $this->actingAs($newClient)->get('/dashboard');
        $clientLoginResponse->assertStatus(200);

        // Verify Ref can immediately allocate product to this new client
        $assignResponse = $this->actingAs($this->ref)->post(route('ref.product-assignments.store'), [
            'client_id' => $newClient->id,
            'product_id' => $this->productPesto->id,
            'quantity' => 15,
            'notes' => 'Initial batch distribution',
        ]);
        $assignResponse->assertRedirect();

        $this->assertDatabaseHas('product_assignments', [
            'client_id' => $newClient->id,
            'product_id' => $this->productPesto->id,
            'quantity' => 15,
        ]);
    }
}
