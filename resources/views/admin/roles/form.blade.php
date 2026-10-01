@extends('admin.layout')

@section('admin-content')
    <h2>{{ $role->exists ? 'Rol bewerken' : 'Rol toevoegen' }}</h2>

    <form method="POST" action="{{ $role->exists ? route('admin.rollen.update', $role) : route('admin.rollen.store') }}" class="mt-3" style="max-width: 32rem">
        @csrf
        @if ($role->exists)
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Naam</label>
            <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required maxlength="255" autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Opslaan</button>
            <a class="btn btn-link" href="{{ route('admin.rollen.index') }}">Annuleren</a>
        </div>
    </form>
@endsection