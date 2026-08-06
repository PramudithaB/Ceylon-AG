<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'business_name' => 'Test Business',
            'nic' => '199512345678',
            'phone' => '0771234567',
            'email' => 'test@example.com',
            'address' => '123 Main St',
            'district' => 'Colombo',
            'province' => 'Western',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('register.success'));
    }
}
