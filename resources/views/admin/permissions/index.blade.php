@extends('admin.layout')

@section('admin-content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Permissies beheren</h2>
        <a class="btn btn-primary" href="{{ route('admin.permissies.create') }}">Permissie toevoegen</a>
    </div>

    @if ($permissions->isEmpty())
        <p>Er zijn nog geen permissies.</p>
    @else
        <table class="table table-striped">
            <thead><tr><th>ID</th><th>Naam</th><th>Guard</th><th>Acties</th></tr></thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->guard_name }}</td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.permissies.edit', $permission) }}">Bewerken</a>
                            <form method="POST" action="{{ route('admin.permissies.destroy', $permission) }}" onsubmit="return confirm('Deze permissie verwijderen?')">
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