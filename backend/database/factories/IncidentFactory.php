<?php

namespace Database\Factories;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncidentFactory extends Factory
{
    protected $model = Incident::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'reported_by' => User::factory(),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'resolved', 'closed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'resolved_at' => $this->faker->optional(0.3)->dateTimeBetween('-1 week', 'now'),
        ];
    }
}