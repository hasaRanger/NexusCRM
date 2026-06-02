<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProposalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_proposals(): void
    {
        $this->get('/proposals')->assertRedirect('/login');
        $this->get('/proposals/create')->assertRedirect('/login');
    }

    public function test_proposals_index_is_rendered(): void
    {
        $user = User::factory()->create();
        Proposal::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/proposals');

        $response->assertOk();
    }

    public function test_create_proposal_page_is_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/proposals/create');

        $response->assertOk();
    }

    public function test_proposal_can_be_stored(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->post('/proposals', [
            'customer_id' => $customer->id,
            'title'       => 'Test Proposal Title',
            'description' => 'Test description details',
            'amount'      => 1500.50,
            'status'      => 'draft',
            'valid_until' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/proposals');

        $this->assertDatabaseHas('proposals', [
            'customer_id' => $customer->id,
            'title'       => 'Test Proposal Title',
            'amount'      => 1500.50,
            'status'      => 'draft',
        ]);
    }

    public function test_edit_proposal_page_is_rendered(): void
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($user)->get("/proposals/{$proposal->id}/edit");

        $response->assertOk();
    }

    public function test_proposal_can_be_updated(): void
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->put("/proposals/{$proposal->id}", [
            'customer_id' => $customer->id,
            'title'       => 'Updated Title',
            'description' => 'Updated desc',
            'amount'      => 2000.00,
            'status'      => 'sent',
            'valid_until' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/proposals');

        $this->assertDatabaseHas('proposals', [
            'id'          => $proposal->id,
            'customer_id' => $customer->id,
            'title'       => 'Updated Title',
            'amount'      => 2000.00,
            'status'      => 'sent',
        ]);
    }

    public function test_proposal_status_can_be_changed(): void
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create([
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user)->patch("/proposals/{$proposal->id}/status", [
            'status' => 'accepted',
        ]);

        $response->assertRedirect();
        $this->assertEquals('accepted', $proposal->fresh()->status);
    }

    public function test_proposal_status_change_validation(): void
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create([
            'status' => 'draft',
        ]);

        $response = $this->actingAs($user)->patch("/proposals/{$proposal->id}/status", [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertEquals('draft', $proposal->fresh()->status);
    }

    public function test_proposal_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $proposal = Proposal::factory()->create();

        $response = $this->actingAs($user)->delete("/proposals/{$proposal->id}");

        $response->assertRedirect('/proposals');

        $this->assertSoftDeleted('proposals', [
            'id' => $proposal->id,
        ]);
    }
}
