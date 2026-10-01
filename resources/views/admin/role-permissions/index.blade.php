@extends('admin.layout')

@section('admin-content')
    <h2>Permissie koppelen aan rol</h2>

    <form method="POST" action="{{ route('admin.role-permissions.store') }}" class="form-row align-items-end mb-4">
        @csrf
        <div class="form-group col-md-5">
            <label for="permission_id">Permissie</label>
            <select id="permission_id" name="permission_id" class="form-control @error('permission_id') is-invalid @enderror" required>
                <option value="">Selecteer een permissie</option>
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}" @selected(old('permission_id') == $permission->id)>{{ $permission->name }} (ID {{ $permission->id }})</option>
                @endforeach
            </select>
            @error('permission_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group col-md-5">
            <label for="role_id">Rol</label>
            <select id="role_id" name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                <option value="">Selecteer een rol</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }} (ID {{ $role->id }})</option>
                @endforeach
            </select>
            @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group col-md-2"><button class="btn btn-primary" type="submit">Koppelen</button></div>
    </form>

    <table class="table table-striped">
        <thead><tr><th>Permissie-ID</th><th>Permissie</th><th>Rol-ID</th><th>Rol</th><th>Acties</th></tr></thead>
        <tbody>
            @forelse ($roles as $role)
                @foreach ($role->permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td><td>{{ $permission->name }}</td>
                        <td>{{ $role->id }}</td><td>{{ $role->name }}</td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.role-permissions.edit', [$role->id, $permission->id]) }}">Bewerken</a>
                            <form method="POST" action="{{ route('admin.role-permissions.destroy', [$role->id, $permission->id]) }}" onsubmit="return confirm('Deze koppeling verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr><td colspan="5">Er zijn nog geen rollen.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection