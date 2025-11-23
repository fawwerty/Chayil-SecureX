<?php

namespace Database\Factories;

use App\Models\TrainingModule;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingModuleFactory extends Factory
{
    protected $model = TrainingModule::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->paragraphs(5, true),
            'description' => $this->faker->paragraph(),
            'duration' => $this->faker->numberBetween(15, 120),
            'is_active' => true,
        ];
    }
}