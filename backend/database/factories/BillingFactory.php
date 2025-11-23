<?php

namespace Database\Factories;

use App\Models\Billing;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingFactory extends Factory
{
    protected $model = Billing::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(['pending', 'paid', 'overdue', 'cancelled']),
            'due_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'paid_date' => $this->faker->optional(0.5)->dateTimeBetween('-1 month', 'now'),
            'invoice_number' => 'INV-' . $this->faker->unique()->numerify('######'),
            'description' => $this->faker->optional()->sentence(),
            // NO user_id here
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_date' => null,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
            'paid_date' => null,
        ]);
    }
}