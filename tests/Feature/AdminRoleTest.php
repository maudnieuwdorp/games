<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_roles(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::findOrCreate('admin', 'web'));

        $this->actingAs($admin)
            ->get(route('admin.rollen.index'))
            ->assertOk();

        $this->post(route('admin.rollen.store'), ['name' => 'moderator'])
            ->assertRedirect(route('admin.rollen.index'));

        $role = Role::where('name', 'moderator')->firstOrFail();
        $this->assertSame('web', $role->guard_name);

        $this->put(route('admin.rollen.update', $role), ['name' => 'editor'])
            ->assertRedirect(route('admin.rollen.index'));
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'editor']);

        $this->delete(route('admin.rollen.destroy', $role))
            ->assertRedirect(route('admin.rollen.index'));
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}