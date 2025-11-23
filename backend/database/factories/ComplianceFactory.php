<?php

namespace Database\Factories;

use App\Models\Compliance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplianceFactory extends Factory
{
    protected $model = Compliance::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'data' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'completed_at' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', 'now'),
        ];
    }
}