<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminUserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_user_role_assignments(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::findOrCreate('admin', 'web'));
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $role = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $otherRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);

        $this->actingAs($admin)
            ->get(route('admin.user-roles.index'))
            ->assertOk();

        $this->post(route('admin.user-roles.store'), [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ])->assertRedirect(route('admin.user-roles.index'));
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $user->id,
            'model_type' => User::class,
        ]);

        $this->put(route('admin.user-roles.update', [$user->id, $role->id]), [
            'user_id' => $otherUser->id,
            'role_id' => $otherRole->id,
        ])->assertRedirect(route('admin.user-roles.index'));
        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $user->id,
            'model_type' => User::class,
        ]);
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $otherRole->id,
            'model_id' => $otherUser->id,
            'model_type' => User::class,
        ]);

        $this->delete(route('admin.user-roles.destroy', [$otherUser->id, $otherRole->id]))
            ->assertRedirect(route('admin.user-roles.index'));
        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $otherRole->id,
            'model_id' => $otherUser->id,
            'model_type' => User::class,
        ]);
    }
}