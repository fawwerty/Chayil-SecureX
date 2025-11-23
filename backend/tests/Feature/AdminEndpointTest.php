<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\AuthenticatesUsers;
use App\Models\User;
use App\Models\Client;
use App\Models\Incident;
use App\Models\Report;
use App\Models\Setting;

class AdminEndpointTest extends TestCase
{
    use AuthenticatesUsers;

    /** @test */
    public function admin_can_view_dashboard_summary()
    {
        $token = $this->actingAsAdmin();

        $response = $this->getJson('/api/admin/summary', $this->getAuthHeaders($token));

        $response->assertStatus(200);
    }

    /** @test */
    public function client_cannot_access_admin_dashboard()
    {
        $token = $this->actingAsClient();

        $response = $this->getJson('/api/admin/summary', $this->getAuthHeaders($token));

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_list_all_clients()
    {
        $token = $this->actingAsAdmin();
        Client::factory()->count(5)->create();

        $response = $this->getJson('/api/admin/clients', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'company_name', 'contact_email']
                     ]
                 ]);
    }

    /** @test */
    public function admin_can_create_client()
    {
        $token = $this->actingAsAdmin();
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);

        $response = $this->postJson('/api/admin/clients', [
            'user_id' => $user->id,
            'company_name' => 'Test Company',
            'contact_email' => 'company@example.com',
            'phone' => '1234567890',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'company_name', 'contact_email']);

        $this->assertDatabaseHas('clients', [
            'company_name' => 'Test Company',
            'contact_email' => 'company@example.com',
        ]);
    }

    /** @test */
    public function admin_can_update_client()
    {
        $token = $this->actingAsAdmin();
        $client = Client::factory()->create();

        $response = $this->patchJson("/api/admin/clients/{$client->id}", [
            'company_name' => 'Updated Company',
            'contact_email' => 'updated@example.com',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJson(['company_name' => 'Updated Company']);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'company_name' => 'Updated Company',
        ]);
    }

    /** @test */
    public function admin_can_delete_client()
    {
        $token = $this->actingAsAdmin();
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/admin/clients/{$client->id}", [], $this->getAuthHeaders($token));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    /** @test */
    public function admin_can_list_all_incidents()
    {
        $token = $this->actingAsAdmin();
        Incident::factory()->count(5)->create();

        $response = $this->getJson('/api/admin/incidents', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'description', 'status']
                     ]
                 ]);
    }

    /** @test */
    public function admin_can_create_incident()
    {
        $token = $this->actingAsAdmin();
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);

        $response = $this->postJson('/api/admin/incidents', [
            'title' => 'Test Incident',
            'description' => 'Test Description',
            'reported_by' => $user->id,
        ], $this->getAuthHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'description']);

        $this->assertDatabaseHas('incidents', [
            'title' => 'Test Incident',
        ]);
    }

    /** @test */
    public function admin_can_update_incident()
    {
        $token = $this->actingAsAdmin();
        $incident = Incident::factory()->create();

        $response = $this->patchJson("/api/admin/incidents/{$incident->id}", [
            'title' => 'Updated Incident',
            'status' => 'resolved',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJson(['title' => 'Updated Incident', 'status' => 'resolved']);

        $this->assertDatabaseHas('incidents', [
            'id' => $incident->id,
            'title' => 'Updated Incident',
            'status' => 'resolved',
        ]);
    }

    /** @test */
    public function admin_can_list_all_reports()
    {
        $token = $this->actingAsAdmin();
        Report::factory()->count(5)->create();

        $response = $this->getJson('/api/admin/reports', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'content']
                     ]
                 ]);
    }

    /** @test */
    public function admin_can_export_reports()
    {
        $token = $this->actingAsAdmin();
        Report::factory()->count(3)->create();

        $response = $this->postJson('/api/admin/reports/export', [
            'format' => 'csv',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_list_all_users()
    {
        $token = $this->actingAsAdmin();
        User::factory()->count(5)->create();

        $response = $this->getJson('/api/admin/users', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'email']
                     ]
                 ]);
    }

    /** @test */
    public function admin_can_update_user_role()
    {
        $token = $this->actingAsAdmin();
        $adminRole = \App\Models\Role::where('name', 'Admin')->first();
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);

        $response = $this->patchJson("/api/admin/roles/{$user->id}", [
            'role_id' => $adminRole->id,
        ], $this->getAuthHeaders($token));

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role_id' => $adminRole->id,
        ]);
    }

    /** @test */
    public function admin_can_view_settings()
    {
        $token = $this->actingAsAdmin();

        $response = $this->getJson('/api/admin/settings', $this->getAuthHeaders($token));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_settings()
    {
        $token = $this->actingAsAdmin();
        Setting::factory()->create(['key' => 'test_key', 'value' => 'old_value']);

        $response = $this->patchJson('/api/admin/settings', [
            'test_key' => 'new_value',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(200);

        $this->assertDatabaseHas('settings', [
            'key' => 'test_key',
            'value' => 'new_value',
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_admin_endpoints()
    {
        $response = $this->getJson('/api/admin/summary');

        $response->assertStatus(401);
    }
}

