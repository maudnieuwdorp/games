@extends('admin.layout')

@section('admin-content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Rollen beheren</h2>
        <a class="btn btn-primary" href="{{ route('admin.rollen.create') }}">Rol toevoegen</a>
    </div>

    @if ($roles->isEmpty())
        <p>Er zijn nog geen rollen.</p>
    @else
        <table class="table table-striped">
            <thead><tr><th>ID</th><th>Naam</th><th>Guard</th><th>Acties</th></tr></thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->guard_name }}</td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.rollen.edit', $role) }}">Bewerken</a>
                            <form method="POST" action="{{ route('admin.rollen.destroy', $role) }}" onsubmit="return confirm('Deze rol verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection