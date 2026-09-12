<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();
        return view('games.index', compact('games'));
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
}
