<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\AuthenticatesUsers;
use App\Models\User;
use App\Models\Client;
use App\Models\Incident;
use App\Models\Report;
use App\Models\Message;
use App\Models\TrainingModule;
use App\Models\TrainingProgress;
use App\Models\Billing;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ClientEndpointTest extends TestCase
{
    use AuthenticatesUsers;

    /** @test */
    public function client_can_view_dashboard()
    {
        $token = $this->actingAsClient();

        $response = $this->getJson('/api/client/dashboard', $this->getAuthHeaders($token));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_cannot_access_client_dashboard()
    {
        $token = $this->actingAsAdmin();

        $response = $this->getJson('/api/client/dashboard', $this->getAuthHeaders($token));

        $response->assertStatus(403);
    }

    /** @test */
    public function client_can_view_own_incidents()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        Incident::factory()->count(3)->create(['reported_by' => $user->id]);
        Incident::factory()->count(2)->create(); // Other user's incidents

        $response = $this->getJson('/api/client/incidents', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function client_can_report_incident()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/client/incidents/report', [
            'title' => 'New Incident',
            'description' => 'Incident description',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'description']);

        $this->assertDatabaseHas('incidents', [
            'title' => 'New Incident',
            'reported_by' => $user->id,
        ]);
    }

    /** @test */
    public function client_can_view_own_reports()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        Report::factory()->count(3)->create(['created_by' => $user->id]);
        Report::factory()->count(2)->create(); // Other user's reports

        $response = $this->getJson('/api/client/reports', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function client_can_view_compliance()
    {
        $token = $this->actingAsClient();

        $response = $this->getJson('/api/client/compliance', $this->getAuthHeaders($token));

        $response->assertStatus(200);
    }

    /** @test */
    public function client_can_update_compliance()
    {
        $token = $this->actingAsClient();

        $response = $this->patchJson('/api/client/compliance', [
            'compliance_data' => 'updated data',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(200);
    }

    /** @test */
    public function client_can_view_training_modules()
    {
        $token = $this->actingAsClient();
        TrainingModule::factory()->count(5)->create();

        $response = $this->getJson('/api/client/training/modules', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'content']
                     ]
                 ]);
    }

    /** @test */
    public function client_can_view_training_progress()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        $module = TrainingModule::factory()->create();
        TrainingProgress::factory()->create([
            'user_id' => $user->id,
            'module_id' => $module->id,
        ]);

        $response = $this->getJson('/api/client/training/progress', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'module_id', 'completed']
                     ]
                 ]);
    }

    /** @test */
    public function client_can_store_training_progress()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        $module = TrainingModule::factory()->create();

        $response = $this->postJson('/api/client/training/progress', [
            'module_id' => $module->id,
            'completed' => false,
        ], $this->getAuthHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'module_id', 'completed']);

        $this->assertDatabaseHas('training_progress', [
            'user_id' => $user->id,
            'module_id' => $module->id,
            'completed' => false,
        ]);
    }

    /** @test */
    public function client_can_view_messages()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        $sender = User::factory()->create();
        Message::factory()->count(3)->create([
            'sender_id' => $sender->id,
            'recipient_id' => $user->id,
        ]);

        $response = $this->getJson('/api/client/messages', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'content', 'sender']
                     ]
                 ]);
    }

    /** @test */
    public function client_can_send_message()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $recipient = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/client/messages', [
            'recipient_id' => $recipient->id,
            'content' => 'Test message content',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'content', 'sender_id', 'recipient_id']);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $user->id,
            'recipient_id' => $recipient->id,
            'content' => 'Test message content',
        ]);
    }

    /** @test */
    public function client_can_view_billing()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        Billing::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/client/billing', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'amount', 'status']
                     ]
                 ]);
    }

    /** @test */
    public function client_can_view_account()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        $response = $this->getJson('/api/client/account', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'name', 'email']);
    }

    /** @test */
    public function client_can_update_account()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user = User::factory()->create(['role_id' => $clientRole->id]);
        $token = JWTAuth::fromUser($user);
        $this->actingAs($user, 'api');

        $response = $this->patchJson('/api/client/account', [
            'name' => 'Updated Name',
            'phone' => '1234567890',
        ], $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJson(['name' => 'Updated Name']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_client_endpoints()
    {
        $response = $this->getJson('/api/client/dashboard');

        $response->assertStatus(401);
    }

    /** @test */
    public function client_cannot_access_other_clients_data()
    {
        $clientRole = \App\Models\Role::where('name', 'Client')->first();
        $user1 = User::factory()->create(['role_id' => $clientRole->id]);
        $user2 = User::factory()->create(['role_id' => $clientRole->id]);
        
        $token = JWTAuth::fromUser($user1);
        $this->actingAs($user1, 'api');

        // User1 should only see their own incidents
        Incident::factory()->create(['reported_by' => $user1->id]);
        Incident::factory()->create(['reported_by' => $user2->id]);

        $response = $this->getJson('/api/client/incidents', $this->getAuthHeaders($token));

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }
}

