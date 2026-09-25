@extends('base')
 
@section('title', '🎮 Game Collection')
 
@section('content')
    @role('admin')
<a href="/games/create" class="btn btn-success mb-3">🎮 Add Game</a>
    @endrole
 
    @php($sum = 0)
 
    <table class="table">
<thead class="thead-dark">
<tr>
<th>ID</th>
<th>Game</th>
<th>Platform</th>
<th>Genre</th>
<th>Rating</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
            @foreach($games as $game)
                @php($sum += $game->rating)
<tr>
<td>{{ $game->id }}</td>
<td>{{ $game->game_name }}</td>
<td>{{ $game->platform }}</td>
<td>{{ $game->genre }}</td>
<td>{{ $game->rating }}/10</td>
<td>
<a href="{{ route('games.show', $game->id) }}" class="btn btn-info btn-sm">Show</a>
 
                        @role('admin')
<a href="/games/edit/{{ $game->id }}" class="btn btn-primary btn-sm">Edit</a>
<form action="/games/destroy/{{ $game->id }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
<button onclick="return confirm('Weet je het zeker?')" class="btn btn-danger btn-sm" type="submit">Delete</button>
</form>
                        @endrole
</td>
</tr>
            @endforeach
<tr>
<td colspan="4"><strong>Gemiddelde rating:</strong></td>
<td><strong>{{ count($games) > 0 ? number_format($sum / count($games), 1) : 0 }}/10</strong></td>
<td></td>
</tr>
</tbody>
</table>
@endsection