@extends('admin.layout')

@section('admin-content')
    <h2>Rol koppelen aan gebruiker</h2>

    <form method="POST" action="{{ route('admin.user-roles.store') }}" class="form-row align-items-end mb-4">
        @csrf
        <div class="form-group col-md-5">
            <label for="user_id">Gebruiker</label>
            <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                <option value="">Selecteer een gebruiker</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} ({{ $user->email }}, ID {{ $user->id }})</option>
                @endforeach
            </select>
            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
        <thead><tr><th>Gebruiker-ID</th><th>Gebruiker</th><th>Rol-ID</th><th>Rol</th><th>Acties</th></tr></thead>
        <tbody>
            @forelse ($assignments as $assignment)
                <tr>
                    <td>{{ $assignment['user']->id }}</td><td>{{ $assignment['user']->name }} ({{ $assignment['user']->email }})</td>
                    <td>{{ $assignment['role']->id }}</td><td>{{ $assignment['role']->name }}</td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.user-roles.edit', [$assignment['user']->id, $assignment['role']->id]) }}">Bewerken</a>
                        <form method="POST" action="{{ route('admin.user-roles.destroy', [$assignment['user']->id, $assignment['role']->id]) }}" onsubmit="return confirm('Deze koppeling verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Er zijn nog geen rolkoppelingen.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection