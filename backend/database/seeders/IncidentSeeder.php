<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing users or create some if none exist
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Creating incidents with factory-generated users.');
            Incident::factory(20)->create();
        } else {
            // Create incidents with existing users
            Incident::factory(20)->create([
                'reported_by' => function () use ($users) {
                    return $users->random()->id;
                }
            ]);
        }
    }
}
