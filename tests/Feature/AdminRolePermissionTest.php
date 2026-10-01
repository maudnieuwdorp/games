<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminRolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_role_permission_assignments(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::findOrCreate('admin', 'web'));
        $role = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $otherRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $permission = Permission::create(['name' => 'manage games', 'guard_name' => 'web']);
        $otherPermission = Permission::create(['name' => 'publish games', 'guard_name' => 'web']);

        $this->actingAs($admin)
            ->get(route('admin.role-permissions.index'))
            ->assertOk();

        $this->post(route('admin.role-permissions.store'), [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ])->assertRedirect(route('admin.role-permissions.index'));
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);

        $this->put(route('admin.role-permissions.update', [$role->id, $permission->id]), [
            'role_id' => $otherRole->id,
            'permission_id' => $otherPermission->id,
        ])->assertRedirect(route('admin.role-permissions.index'));
        $this->assertDatabaseMissing('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $otherRole->id,
            'permission_id' => $otherPermission->id,
        ]);

        $this->delete(route('admin.role-permissions.destroy', [$otherRole->id, $otherPermission->id]))
            ->assertRedirect(route('admin.role-permissions.index'));
        $this->assertDatabaseMissing('role_has_permissions', [
            'role_id' => $otherRole->id,
            'permission_id' => $otherPermission->id,
        ]);
    }
}