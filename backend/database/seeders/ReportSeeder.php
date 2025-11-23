<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing users or create some if none exist
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Creating reports with factory-generated users.');
            Report::factory(10)->create();
        } else {
            // Create reports with existing users
            Report::factory(10)->create([
                'created_by' => function () use ($users) {
                    return $users->random()->id;
                }
            ]);
        }
    }
}
