<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();

        if (!$adminRole) {
            $this->command->info('Admin role not found, run RoleSeeder first.');
            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@chayil.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id
            ]
        );
    }
}
