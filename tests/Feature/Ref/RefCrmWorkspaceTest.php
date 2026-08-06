<?php

namespace Tests\Feature\Ref;

use App\Models\ClientNote;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StockRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefCrmWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected User $refUser;
    protected User $assignedClient;
    protected User $otherClient;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Sales Rep
        $this->refUser = User::factory()->create([
            'role' => User::ROLE_REF,
            'status' => User::STATUS_APPROVED,
        ]);

        // Create Client assigned to this Sales Rep
        $this->assignedClient = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $this->refUser->id,
            'business_name' => 'Green Valley Farm',
        ]);

        // Create Another Sales Rep
        $otherRef = User::factory()->create([
            'role' => User::ROLE_REF,
            'status' => User::STATUS_APPROVED,
        ]);

        // Create Client assigned to another Sales Rep
        $this->otherClient = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'ref_id' => $otherRef->id,
            'business_name' => 'Sunrise Agros',
        ]);

        // Create Category & Product
        $category = \App\Models\Category::create([
            'name' => 'Fertilizers',
            'slug' => 'fertilizers',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Organic Fertilizer 20kg',
            'sku' => 'FERT-001',
            'stock' => 100,
            'buying_price' => 2000.00,
            'dealer_price' => 2500.00,
            'selling_price' => 3000.00,
            'status' => 'active',
        ]);
    }

    /** @test */
    public function ref_sees_please_select_a_client_on_initial_dashboard_visit()
    {
        $response = $this->actingAs($this->refUser)
            ->get(route('ref.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Please select a client.');
        $response->assertSee('Select Client');
    }

    /** @test */
    public function ref_with_no_clients_in_system_sees_empty_state_message()
    {
        User::where('role', User::ROLE_CLIENT)->delete();

        $unassignedRef = User::factory()->create([
            'role' => User::ROLE_REF,
            'status' => User::STATUS_APPROVED,
        ]);

        $response = $this->actingAs($unassignedRef)
            ->get(route('ref.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('You currently have no assigned clients.');
    }

    /** @test */
    public function ref_can_access_single_client_workspace_dashboard()
    {
        $response = $this->actingAs($this->refUser)
            ->get(route('ref.dashboard', ['client_id' => $this->assignedClient->id]));

        $response->assertStatus(200);
        $response->assertSee('Green Valley Farm');
        $response->assertSee('Select Client');
    }

    /** @test */
    public function ref_can_switch_client_workspace_via_ajax()
    {
        $response = $this->actingAs($this->refUser)
            ->get(route('ref.workspace.client', $this->assignedClient->id), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
                'HTTP_ACCEPT' => 'application/json',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'client_id' => $this->assignedClient->id,
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_client_workspace()
    {
        $response = $this->get(route('ref.workspace.client', $this->assignedClient->id));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function ref_can_record_payment_for_assigned_client()
    {
        $response = $this->actingAs($this->refUser)
            ->post(route('ref.workspace.payments.store', $this->assignedClient->id), [
                'payment_method' => 'bank_transfer',
                'amount' => 15000.00,
                'payment_date' => date('Y-m-d'),
                'reference_number' => 'REF-BANK-99',
                'bank_name' => 'Commercial Bank',
                'remarks' => 'Advance payment collected',
            ]);

        $response->assertRedirect(route('ref.dashboard', ['client_id' => $this->assignedClient->id]));

        $this->assertDatabaseHas('payments', [
            'client_id' => $this->assignedClient->id,
            'collected_by' => $this->refUser->id,
            'amount' => 15000.00,
            'status' => Payment::STATUS_PENDING,
        ]);
    }

    /** @test */
    public function ref_can_submit_stock_request_for_assigned_client()
    {
        $response = $this->actingAs($this->refUser)
            ->post(route('ref.workspace.stock-requests.store', $this->assignedClient->id), [
                'product_id' => $this->product->id,
                'requested_quantity' => 20,
                'priority' => 'high',
                'reason' => 'Client expansion demand',
            ]);

        $response->assertRedirect(route('ref.dashboard', ['client_id' => $this->assignedClient->id]));

        $this->assertDatabaseHas('stock_requests', [
            'client_id' => $this->assignedClient->id,
            'product_id' => $this->product->id,
            'requested_quantity' => 20,
            'priority' => 'high',
            'status' => StockRequest::STATUS_PENDING,
        ]);
    }

    /** @test */
    public function ref_can_add_note_to_client_workspace()
    {
        $response = $this->actingAs($this->refUser)
            ->post(route('ref.workspace.notes.store', $this->assignedClient->id), [
                'content' => 'Discussed quarter 3 fertilizer inventory requirement with store manager.',
            ]);

        $response->assertRedirect(route('ref.dashboard', ['client_id' => $this->assignedClient->id]));

        $this->assertDatabaseHas('client_notes', [
            'client_id' => $this->assignedClient->id,
            'ref_id' => $this->refUser->id,
            'content' => 'Discussed quarter 3 fertilizer inventory requirement with store manager.',
        ]);
    }
}
