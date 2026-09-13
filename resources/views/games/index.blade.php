<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Games Overzicht</title>
</head>
<body>
    <div class="container" style="margin:40px;">
        <h1 class="display-4">🎮 Games</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="/games/create" class="btn btn-success mb-3">🎮 Add Game</a>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Game Name</th>
                    <th>Platform</th>
                    <th>Genre</th>
                    <th>Rating</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($games as $game)
                    <tr>
                        <td>{{ $game->id }}</td>
                        <td>{{ $game->game_name }}</td>
                        <td>{{ $game->platform }}</td>
                        <td>{{ $game->genre }}</td>
                        <td>{{ $game->rating }}</td>
                        <td>
                            <a href="/games/edit/{{ $game->id }}" class="btn btn-primary btn-sm">Edit</a>
                        </td>
                        <td>
                            <form action="/games/destroy/{{ $game->id }}" method="post">
                                @csrf
                                <button onclick="return confirm('Weet je het zeker?')" class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
