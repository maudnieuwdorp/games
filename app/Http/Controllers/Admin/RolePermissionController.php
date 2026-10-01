<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        return view('admin.role-permissions.index', [
            'roles' => Role::where('guard_name', 'web')->with(['permissions' => fn ($query) => $query->where('guard_name', 'web')])->orderBy('name')->get(),
            'permissions' => Permission::where('guard_name', 'web')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $role = Role::where('guard_name', 'web')->findOrFail($validated['role_id']);
        $permission = Permission::where('guard_name', 'web')->findOrFail($validated['permission_id']);
        $role->givePermissionTo($permission);

        return redirect()->route('admin.role-permissions.index')->with('status', 'Permissie aan rol gekoppeld.');
    }

    public function edit(int $roleId, int $permissionId): View
    {
        return view('admin.role-permissions.form', [
            'roles' => Role::where('guard_name', 'web')->orderBy('name')->get(),
            'permissions' => Permission::where('guard_name', 'web')->orderBy('name')->get(),
            'roleId' => $roleId,
            'permissionId' => $permissionId,
        ]);
    }

    public function update(Request $request, int $roleId, int $permissionId): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $oldRole = Role::where('guard_name', 'web')->findOrFail($roleId);
        $oldPermission = Permission::where('guard_name', 'web')->findOrFail($permissionId);
        $newRole = Role::where('guard_name', 'web')->findOrFail($validated['role_id']);
        $newPermission = Permission::where('guard_name', 'web')->findOrFail($validated['permission_id']);

        DB::transaction(function () use ($oldRole, $oldPermission, $newRole, $newPermission) {
            $oldRole->revokePermissionTo($oldPermission);
            $newRole->givePermissionTo($newPermission);
        });

        return redirect()->route('admin.role-permissions.index')->with('status', 'Koppeling bijgewerkt.');
    }

    public function destroy(int $roleId, int $permissionId): RedirectResponse
    {
        $role = Role::where('guard_name', 'web')->findOrFail($roleId);
        $permission = Permission::where('guard_name', 'web')->findOrFail($permissionId);
        $role->revokePermissionTo($permission);

        return redirect()->route('admin.role-permissions.index')->with('status', 'Koppeling verwijderd.');
    }

    private function rules(): array
    {
        return [
            'role_id' => ['required', Rule::exists('roles', 'id')->where('guard_name', 'web')],
            'permission_id' => ['required', Rule::exists('permissions', 'id')->where('guard_name', 'web')],
        ];
    }
}