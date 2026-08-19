<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_with_only_role_column_can_access_admin_modules(): void
    {
        // Create an admin user with ONLY the role column set to 'admin' (no Spatie roles assigned)
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin);

        // Verify all Admin routes return 200 OK
        $responseDashboard = $this->get('/admin/dashboard');
        $responseDashboard->assertStatus(200);

        $responseClients = $this->get('/admin/clients');
        $responseClients->assertStatus(200);

        $responseSales = $this->get('/admin/sales');
        $responseSales->assertStatus(200);

        $responsePayments = $this->get('/admin/payments');
        $responsePayments->assertStatus(200);

        $responseProducts = $this->get('/admin/products');
        $responseProducts->assertStatus(200);

        $responseCategories = $this->get('/admin/categories');
        $responseCategories->assertStatus(200);

        $responseQuotations = $this->get('/admin/quotations');
        $responseQuotations->assertStatus(200);

        $responseReports = $this->get('/admin/reports');
        $responseReports->assertStatus(200);
    }

    public function test_client_user_cannot_access_admin_routes(): void
    {
        $client = User::factory()->create([
            'role' => User::ROLE_CLIENT,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($client);

        $this->get('/admin/dashboard')->assertStatus(403);
        $this->get('/admin/clients')->assertStatus(403);
        $this->get('/admin/sales')->assertStatus(403);
        $this->get('/admin/payments')->assertStatus(403);
        $this->get('/admin/products')->assertStatus(403);
    }

    public function test_ref_user_cannot_access_admin_routes(): void
    {
        $ref = User::factory()->create([
            'role' => User::ROLE_REF,
            'status' => User::STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($ref);

        $this->get('/admin/dashboard')->assertStatus(403);
        $this->get('/admin/clients')->assertStatus(403);
        $this->get('/admin/sales')->assertStatus(403);
        $this->get('/admin/payments')->assertStatus(403);
        $this->get('/admin/products')->assertStatus(403);
    }
}
