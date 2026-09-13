<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();
        return view('games.index', ['games' => $games]);
    }

    public function create()
    {
        return view('games.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_name' => 'required',
            'platform' => 'required',
            'genre' => 'required',
            'rating' => 'required|numeric|min:0|max:10'
        ]);

        $game = new Game([
            'game_name' => $request->get('game_name'),
            'platform' => $request->get('platform'),
            'genre' => $request->get('genre'),
            'rating' => $request->get('rating')
        ]);

        $game->save();

        return redirect('/games')->with('success', 'Game added!');
    }

    public function edit($id)
    {
        $game = Game::find($id);
        return view('games.edit', ['game' => $game]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'game_name' => 'required',
            'platform' => 'required',
            'genre' => 'required',
            'rating' => 'required|numeric|min:0|max:10'
        ]);

        $game = Game::find($id);
        $game->game_name = $request->get('game_name');
        $game->platform = $request->get('platform');
        $game->genre = $request->get('genre');
        $game->rating = $request->get('rating');
        $game->save();

        return redirect('/games');
    }

    public function destroy($id)
    {
        $game = Game::find($id);
        $game->delete();
        return redirect('/games');
    }
}
