<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\Role;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

trait AuthenticatesUsers
{
    /**
     * Create an admin user and return JWT token
     */
    protected function actingAsAdmin(): string
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $user = User::factory()->create(['role_id' => $adminRole->id]);
        $token = JWTAuth::fromUser($user);
        
        $this->actingAs($user, 'api');
        
        return $token;
    }

    /**
     * Create a client user and return JWT token
     */
    protected function actingAsClient(): string
    {
        $clientRole = Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        
        $this->actingAs($user, 'api');
        
        return $token;
    }

    /**
     * Get authorization header with token
     */
    protected function getAuthHeaders(string $token): array
    {
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }
}

