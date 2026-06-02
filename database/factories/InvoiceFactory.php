<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $count = 0;
        $count++;

        return [
            'customer_id'    => Customer::factory(),
            'invoice_number' => 'INV-' . str_pad($count, 4, '0', STR_PAD_LEFT),
            'amount'         => fake()->randomFloat(2, 50, 2000),
            'tax'            => fake()->randomFloat(2, 5, 200),
            'due_date'       => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status'         => fake()->randomElement(['draft', 'sent', 'paid']),
        ];
    }
}
