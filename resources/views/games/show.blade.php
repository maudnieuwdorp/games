@extends('base')

@section('title', 'Game details')

@section('content')
    <div class="container">


        <table class="table">
            <tr>
                 <th>Game name</th>
                 <td>{{ $game->game_name }}</td>
            </tr>

            <tr>
                <th>Platform</th>
                <td>{{ $game->platform }}</td>
            </tr>
            <tr>
                <th>Genre</th>
                <td>{{ $game->genre }}</td>
            </tr>
            <tr>
                <th>Rating</th>
                <td>{{ $game->rating }}</td>
            </tr>
        </table>

        <a href="{{ route('games.index') }}" class="btn btn-secondary">Terug naar overzicht</a>
    </div>
@endsection
