@extends('base')

@section('title', 'Beheeromgeving')

@section('content')
    <nav class="mb-4" aria-label="Beheermenu">
        <a class="btn btn-outline-secondary" href="{{ route('admin.permissies.index') }}">Permissies</a>
        <a class="btn btn-outline-secondary" href="{{ route('admin.rollen.index') }}">Rollen</a>
    </nav>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    @yield('admin-content')
@endsection