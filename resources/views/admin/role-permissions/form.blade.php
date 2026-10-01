@extends('admin.layout')

@section('admin-content')
    <h2>Permissiekoppeling bewerken</h2>

    <form method="POST" action="{{ route('admin.role-permissions.update', [$roleId, $permissionId]) }}" class="mt-3" style="max-width: 32rem">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="permission_id">Permissie</label>
            <select id="permission_id" name="permission_id" class="form-control" required>
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}" @selected(old('permission_id', $permissionId) == $permission->id)>{{ $permission->name }} (ID {{ $permission->id }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="role_id">Rol</label>
            <select id="role_id" name="role_id" class="form-control" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $roleId) == $role->id)>{{ $role->name }} (ID {{ $role->id }})</option>
                @endforeach
            </select>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Opslaan</button>
            <a class="btn btn-link" href="{{ route('admin.role-permissions.index') }}">Annuleren</a>
        </div>
    </form>
@endsection