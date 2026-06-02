<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    protected $model = Proposal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'title'       => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'amount'      => fake()->randomFloat(2, 100, 5000),
            'status'      => fake()->randomElement(['draft', 'sent', 'accepted', 'rejected']),
            'valid_until' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ];
    }
}
