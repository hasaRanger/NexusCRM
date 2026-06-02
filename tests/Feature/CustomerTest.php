<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_customers(): void
    {
        $this->get('/customers')->assertRedirect('/login');
        $this->get('/customers/create')->assertRedirect('/login');
    }

    public function test_customers_index_is_rendered(): void
    {
        $user = User::factory()->create();
        Customer::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/customers');

        $response->assertOk();
    }

    public function test_create_customer_page_is_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/customers/create');

        $response->assertOk();
    }

    public function test_customer_can_be_stored(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/customers', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'company' => 'ACME Corp',
            'address' => '123 Main St',
            'status' => 'active',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/customers');

        $this->assertDatabaseHas('customers', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'status' => 'active',
        ]);
    }

    public function test_edit_customer_page_is_rendered(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->get("/customers/{$customer->id}/edit");

        $response->assertOk();
    }

    public function test_customer_can_be_updated(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)->put("/customers/{$customer->id}", [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phone' => '0987654321',
            'company' => 'New Corp',
            'address' => '321 New St',
            'status' => 'inactive',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/customers');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
            'status' => 'inactive',
        ]);
    }

    public function test_customer_status_can_be_toggled(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create([
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->patch("/customers/{$customer->id}/status");

        $response->assertRedirect();
        $this->assertEquals('inactive', $customer->fresh()->status);

        $response = $this->actingAs($user)->patch("/customers/{$customer->id}/status");

        $response->assertRedirect();
        $this->assertEquals('active', $customer->fresh()->status);
    }

    public function test_customer_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->delete("/customers/{$customer->id}");

        $response->assertRedirect('/customers');

        // Customer uses SoftDeletes
        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);
    }
}
