@extends('admin.layout')

@section('admin-content')
    <h2>{{ $permission->exists ? 'Permissie bewerken' : 'Permissie toevoegen' }}</h2>

    <form method="POST" action="{{ $permission->exists ? route('admin.permissies.update', $permission) : route('admin.permissies.store') }}" class="mt-3" style="max-width: 32rem">
        @csrf
        @if ($permission->exists)
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Naam</label>
            <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $permission->name) }}" required maxlength="255" autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Opslaan</button>
            <a class="btn btn-link" href="{{ route('admin.permissies.index') }}">Annuleren</a>
        </div>
    </form>
@endsection