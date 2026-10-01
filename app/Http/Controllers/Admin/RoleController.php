<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => Role::where('guard_name', 'web')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.roles.form', ['role' => new Role()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->where('guard_name', 'web')],
        ]);

        Role::create(['name' => $validated['name'], 'guard_name' => 'web']);

        return redirect()->route('admin.rollen.index')->with('status', 'Rol aangemaakt.');
    }

    public function edit(Role $rol): View
    {
        return view('admin.roles.form', ['role' => $rol]);
    }

    public function update(Request $request, Role $rol): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->where('guard_name', 'web')->ignore($rol->id)],
        ]);

        $rol->update(['name' => $validated['name'], 'guard_name' => 'web']);

        return redirect()->route('admin.rollen.index')->with('status', 'Rol bijgewerkt.');
    }

    public function destroy(Role $rol): RedirectResponse
    {
        $rol->delete();

        return redirect()->route('admin.rollen.index')->with('status', 'Rol verwijderd.');
    }
}