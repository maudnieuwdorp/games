<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GameAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_only_view_the_game_overview(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole(Role::findOrCreate('klant', 'web'));

        $this->actingAs($customer)->get('/games')->assertOk();
        $this->get('/games/create')->assertForbidden();
        $this->get('/games/1')->assertForbidden();
        $this->get('/dashboard')->assertForbidden();
        $this->get('/profile')->assertForbidden();
    }

    public function test_admin_can_access_the_game_pages_and_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::findOrCreate('admin', 'web'));
        $game = Game::create([
            'game_name' => 'Test game',
            'platform' => 'PC',
            'genre' => 'Adventure',
            'rating' => 8,
        ]);

        $this->actingAs($admin)->get('/games')->assertOk();
        $this->get('/games/create')->assertOk();
        $this->get('/games/'.$game->id)->assertOk();
        $this->get('/dashboard')->assertOk();
        $this->get('/profile')->assertOk();
    }

    public function test_database_seeder_creates_roles_and_assigns_existing_users_as_customers(): void
    {
        $existingUser = User::factory()->create();

        $this->seed();
        $this->seed();

        $this->assertTrue($existingUser->fresh()->hasRole('klant'));
        $this->assertDatabaseCount('roles', 2);
        $this->assertDatabaseCount('permissions', 4);
    }
}