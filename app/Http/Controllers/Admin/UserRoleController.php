<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles' => fn ($query) => $query->where('guard_name', 'web')])->orderBy('name')->get();
        $assignments = $users->flatMap(fn (User $user) => $user->roles->map(fn (Role $role) => [
            'user' => $user,
            'role' => $role,
        ]));

        return view('admin.user-roles.index', [
            'assignments' => $assignments,
            'roles' => Role::where('guard_name', 'web')->orderBy('name')->get(),
            'users' => $users,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $user = User::findOrFail($validated['user_id']);
        $role = Role::where('guard_name', 'web')->findOrFail($validated['role_id']);
        $user->assignRole($role);

        return redirect()->route('admin.user-roles.index')->with('status', 'Rol aan gebruiker gekoppeld.');
    }

    public function edit(int $userId, int $roleId): View
    {
        return view('admin.user-roles.form', [
            'users' => User::orderBy('name')->get(),
            'roles' => Role::where('guard_name', 'web')->orderBy('name')->get(),
            'userId' => $userId,
            'roleId' => $roleId,
        ]);
    }

    public function update(Request $request, int $userId, int $roleId): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $oldUser = User::findOrFail($userId);
        $oldRole = Role::where('guard_name', 'web')->findOrFail($roleId);
        $newUser = User::findOrFail($validated['user_id']);
        $newRole = Role::where('guard_name', 'web')->findOrFail($validated['role_id']);

        DB::transaction(function () use ($oldUser, $oldRole, $newUser, $newRole) {
            $oldUser->removeRole($oldRole);
            $newUser->assignRole($newRole);
        });

        return redirect()->route('admin.user-roles.index')->with('status', 'Koppeling bijgewerkt.');
    }

    public function destroy(int $userId, int $roleId): RedirectResponse
    {
        $user = User::findOrFail($userId);
        $role = Role::where('guard_name', 'web')->findOrFail($roleId);
        $user->removeRole($role);

        return redirect()->route('admin.user-roles.index')->with('status', 'Koppeling verwijderd.');
    }

    private function rules(): array
    {
        return [
            'user_id' => ['required', Rule::exists('users', 'id')],
            'role_id' => ['required', Rule::exists('roles', 'id')->where('guard_name', 'web')],
        ];
    }
}