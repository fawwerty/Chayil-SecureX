<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $clientRole = Role::where('name', 'Client')->first();

        if (!$adminRole || !$clientRole) {
            $this->command->error('Roles not found. Please run RoleSeeder first.');
            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id
            ]
        );

        User::factory(5)->create([
            'role_id' => $clientRole->id
        ]);
    }
}
