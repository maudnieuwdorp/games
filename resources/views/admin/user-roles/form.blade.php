@extends('admin.layout')

@section('admin-content')
    <h2>Gebruiker-rolkoppeling bewerken</h2>

    <form method="POST" action="{{ route('admin.user-roles.update', [$userId, $roleId]) }}" class="mt-3" style="max-width: 32rem">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">Gebruiker</label>
            <select id="user_id" name="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $userId) == $user->id)>{{ $user->name }} ({{ $user->email }}, ID {{ $user->id }})</option>
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
            <a class="btn btn-link" href="{{ route('admin.user-roles.index') }}">Annuleren</a>
        </div>
    </form>
@endsection