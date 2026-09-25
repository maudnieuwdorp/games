<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
 
Route::get('/', function () {
    return view('welcome');
});
 
// Publieke game routes (iedereen mag kijken)
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/show/{id}', [GameController::class, 'show'])->name('games.show');
 
// Beveiligde game routes (alleen voor ingelogde gebruikers)
Route::middleware('auth')->group(function () {
    Route::get('/games/create', [GameController::class, 'create']);
    Route::post('/games/store', [GameController::class, 'store']);
    Route::get('/games/edit/{id}', [GameController::class, 'edit']);
    Route::post('/games/update/{id}', [GameController::class, 'update']);
    Route::post('/games/destroy/{id}', [GameController::class, 'destroy']);
   
    Route::get('/geheim', function () {
        return view('geheim');
    });
});
 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
 
require __DIR__.'/auth.php';