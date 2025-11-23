<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set JWT secret for testing if not already set
        if (!config('jwt.secret')) {
            config(['jwt.secret' => 'test-secret-key-for-jwt-auth-testing-only']);
        }
        
        // Create roles for testing if they don't exist
        \App\Models\Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Full system access']
        );
        \App\Models\Role::firstOrCreate(
            ['name' => 'Client'],
            ['description' => 'Client access only']
        );
    }
}
