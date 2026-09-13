<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});

// Overzicht
Route::get('games', [GameController::class, 'index']);

// Les 5 - Create
Route::get('games/create', [GameController::class, 'create']);
Route::post('games/store', [GameController::class, 'store']);

// Les 6 - Update
Route::get('games/edit/{id}', [GameController::class, 'edit']);
Route::post('games/update/{id}', [GameController::class, 'update']);

// Les 7 - Delete
Route::post('games/destroy/{id}', [GameController::class, 'destroy']);
