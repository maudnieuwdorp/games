<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    // Toon het overzicht van alle games
    public function index()
    {
        $games = Game::all();
        return view('games.index', ['games' => $games]);
    }

    // Toon het formulier om een nieuwe game toe te voegen
    public function create()
    {
        return view('games.create');
    }

    // Sla een nieuwe game op
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

    // Toon het formulier om een bestaande game te bewerken
    public function edit($id)
    {
        $game = Game::find($id);
        return view('games.edit', ['game' => $game]);
    }

    // Werk een bestaande game bij
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
}
