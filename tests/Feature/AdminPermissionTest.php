<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_permissions(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::findOrCreate('admin', 'web'));

        $this->actingAs($admin)
            ->get(route('admin.permissies.index'))
            ->assertOk();

        $this->post(route('admin.permissies.store'), ['name' => 'manage games'])
            ->assertRedirect(route('admin.permissies.index'));

        $permission = Permission::where('name', 'manage games')->firstOrFail();
        $this->assertSame('web', $permission->guard_name);

        $this->put(route('admin.permissies.update', $permission), ['name' => 'manage catalog'])
            ->assertRedirect(route('admin.permissies.index'));
        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => 'manage catalog']);

        $this->delete(route('admin.permissies.destroy', $permission))
            ->assertRedirect(route('admin.permissies.index'));
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    public function test_non_admin_cannot_access_permission_management(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole(Role::findOrCreate('klant', 'web'));

        $this->actingAs($customer)
            ->get(route('admin.permissies.index'))
            ->assertForbidden();
    }
}