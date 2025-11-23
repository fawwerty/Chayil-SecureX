<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clientRole = Role::where('name', 'Client')->first();
        
        if (!$clientRole) {
            $this->command->error('Client role not found. Please run RoleSeeder first.');
            return;
        }

        // Get existing client users or create new ones
        $clientUsers = User::where('role_id', $clientRole->id)->get();
        
        if ($clientUsers->count() < 10) {
            // Create additional users if needed
            $needed = 10 - $clientUsers->count();
            $newUsers = User::factory($needed)->create([
                'role_id' => $clientRole->id
            ]);
            $clientUsers = $clientUsers->merge($newUsers);
        }

        // Create clients for the first 10 users
        foreach ($clientUsers->take(10) as $user) {
            Client::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => fake()->company(),
                    'contact_email' => $user->email,
                    'phone' => fake()->phoneNumber(),
                    'meta' => []
                ]
            );
        }
    }
}
