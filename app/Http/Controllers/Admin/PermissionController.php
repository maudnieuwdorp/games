<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        return view('admin.permissions.index', [
            'permissions' => Permission::where('guard_name', 'web')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.permissions.form', ['permission' => new Permission()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->where('guard_name', 'web')],
        ]);

        Permission::create(['name' => $validated['name'], 'guard_name' => 'web']);

        return redirect()->route('admin.permissies.index')->with('status', 'Permissie aangemaakt.');
    }

    public function edit(Permission $permissie): View
    {
        return view('admin.permissions.form', ['permission' => $permissie]);
    }

    public function update(Request $request, Permission $permissie): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->where('guard_name', 'web')->ignore($permissie->id)],
        ]);

        $permissie->update(['name' => $validated['name'], 'guard_name' => 'web']);

        return redirect()->route('admin.permissies.index')->with('status', 'Permissie bijgewerkt.');
    }

    public function destroy(Permission $permissie): RedirectResponse
    {
        $permissie->delete();

        return redirect()->route('admin.permissies.index')->with('status', 'Permissie verwijderd.');
    }
}