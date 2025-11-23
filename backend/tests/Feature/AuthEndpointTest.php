<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\AuthenticatesUsers;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthEndpointTest extends TestCase
{
    use AuthenticatesUsers;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $clientRole = Role::where('name', 'Client')->first();
        
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $clientRole->id,
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email'],
                     'access_token'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    /** @test */
    public function registration_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password', 'name', 'role_id']);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $clientRole = Role::where('name', 'Client')->first();
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $clientRole->id,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'user' => ['id', 'name', 'email']
                 ]);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Invalid credentials']);
    }

    /** @test */
    public function user_can_refresh_token()
    {
        $token = $this->actingAsClient();

        $response = $this->postJson('/api/auth/refresh', [], $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token']);
    }

    /** @test */
    public function refresh_fails_without_token()
    {
        $response = $this->postJson('/api/auth/refresh');

        $response->assertStatus(401);
    }

    /** @test */
    public function user_can_request_password_reset()
    {
        $clientRole = Role::where('name', 'Client')->first();
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'role_id' => $clientRole->id,
        ]);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'test@example.com',
        ]);

        // Password reset may return different statuses depending on configuration
        $response->assertStatus(200);
    }

    /** @test */
    public function forgot_password_requires_valid_email()
    {
        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function user_can_verify_2fa()
    {
        $clientRole = Role::where('name', 'Client')->first();
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'two_factor_enabled' => true,
            'two_factor_code' => '123456',
            'two_factor_expires_at' => now()->addMinutes(10),
            'role_id' => $clientRole->id,
        ]);

        $response = $this->postJson('/api/auth/verify-2fa', [
            'email' => 'test@example.com',
            'token' => '123456',
        ]);

        // Adjust based on actual 2FA implementation
        $response->assertStatus(200);
    }
}

