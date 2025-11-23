<?php

namespace Database\Factories;

use App\Models\TrainingProgress;
use App\Models\User;
use App\Models\TrainingModule;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingProgressFactory extends Factory
{
    protected $model = TrainingProgress::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'module_id' => TrainingModule::factory(),
            'completed' => $this->faker->boolean(60),
            'score' => $this->faker->optional(0.7)->numberBetween(0, 100),
            'completed_at' => $this->faker->optional(0.6)->dateTimeBetween('-1 month', 'now'),
        ];
    }
}