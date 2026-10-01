<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// Zorg dat gebruikers ingelogd moeten zijn via auth
Route::middleware(['auth'])->group(function () {

    // 1. Routes toegankelijk voor KLANT en ADMIN (alleen bekijken)
    Route::middleware(['role:klant|admin'])->group(function () {
        Route::get('/games', [GameController::class, 'index'])->name('games.index');
        Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');
    });

    // 2. Routes ALLEEN toegankelijk voor ADMIN (toevoegen, bewerken, verwijderen)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
        Route::post('/games', [GameController::class, 'store'])->name('games.store');
        Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
        Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
        Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
    });

});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('beheer')->name('admin.')->group(function () {
    Route::resource('permissies', PermissionController::class)
        ->parameters(['permissies' => 'permissie'])
        ->except(['show']);
});
