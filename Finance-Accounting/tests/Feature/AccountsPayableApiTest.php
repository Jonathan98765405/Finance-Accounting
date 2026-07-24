<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountsPayableApiTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser()
    {
        return User::forceCreate([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_can_fetch_suppliers()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ap/suppliers');

        $response->assertStatus(200);
    }

    public function test_can_fetch_purchase_orders()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ap/purchase-orders');

        $response->assertStatus(200);
    }

    public function test_can_fetch_invoices()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ap/invoices');

        $response->assertStatus(200);
    }

    public function test_can_fetch_payments()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ap/payments');

        $response->assertStatus(200);
    }

    public function test_can_fetch_dashboard()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/ap/dashboard');

        $response->assertStatus(200);
    }
}