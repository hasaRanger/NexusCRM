<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_invoices(): void
    {
        $this->get('/invoices')->assertRedirect('/login');
        $this->get('/invoices/create')->assertRedirect('/login');
    }

    public function test_invoices_index_is_rendered(): void
    {
        $user = User::factory()->create();
        Invoice::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/invoices');

        $response->assertOk();
    }

    public function test_create_invoice_page_is_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/invoices/create');

        $response->assertOk();
    }

    public function test_invoice_can_be_stored_and_autogenerates_number(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->post('/invoices', [
            'customer_id' => $customer->id,
            'amount'      => 1200.50,
            'tax'         => 80.00,
            'due_date'    => now()->addDays(30)->format('Y-m-d'),
            'status'      => 'draft',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/invoices');

        $this->assertDatabaseHas('invoices', [
            'customer_id'    => $customer->id,
            'invoice_number' => 'INV-0001',
            'amount'         => 1200.50,
            'tax'            => 80.00,
            'status'         => 'draft',
        ]);
    }

    public function test_edit_invoice_page_is_rendered(): void
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create();

        $response = $this->actingAs($user)->get("/invoices/{$invoice->id}/edit");

        $response->assertOk();
    }

    public function test_invoice_can_be_updated(): void
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->put("/invoices/{$invoice->id}", [
            'customer_id' => $customer->id,
            'amount'      => 1500.00,
            'tax'         => 120.00,
            'due_date'    => now()->addDays(15)->format('Y-m-d'),
            'status'      => 'sent',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/invoices');

        $this->assertDatabaseHas('invoices', [
            'id'          => $invoice->id,
            'customer_id' => $customer->id,
            'amount'      => 1500.00,
            'tax'         => 120.00,
            'status'      => 'sent',
        ]);
    }

    public function test_invoice_status_can_be_changed(): void
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create([
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user)->patch("/invoices/{$invoice->id}/status", [
            'status' => 'paid',
        ]);

        $response->assertRedirect();
        $this->assertEquals('paid', $invoice->fresh()->status);
    }

    public function test_invoice_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $invoice = Invoice::factory()->create();

        $response = $this->actingAs($user)->delete("/invoices/{$invoice->id}");

        $response->assertRedirect('/invoices');

        $this->assertSoftDeleted('invoices', [
            'id' => $invoice->id,
        ]);
    }
}
