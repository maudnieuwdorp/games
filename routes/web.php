<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserRoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Algemene ingelogde routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profiel beheren
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Games: Bekijken (Klant + Admin)
    Route::middleware(['role:klant|admin'])->group(function () {
        Route::get('/games', [GameController::class, 'index'])->name('games.index');
        Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');
    });

    // Games: Beheren (Alleen Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
        Route::post('/games', [GameController::class, 'store'])->name('games.store');
        Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
        Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
        Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
    });
});

// Admin Beheeromgeving (Spatie Rollen & Permissies)
Route::middleware(['auth', 'role:admin'])->prefix('beheer')->name('admin.')->group(function () {
    
    // 1. Permissies CRUD
    Route::resource('permissies', PermissionController::class)
        ->parameters(['permissies' => 'permissie'])
        ->except(['show']);

    // 2. Rollen CRUD
    Route::resource('rollen', RoleController::class)
        ->parameters(['rollen' => 'rol'])
        ->except(['show']);

    // 3. Permissie koppelen aan Rol (role_has_permissions)
    Route::get('rol-permissies', [RolePermissionController::class, 'index'])->name('role-permissions.index');
    Route::post('rol-permissies', [RolePermissionController::class, 'store'])->name('role-permissions.store');
    Route::get('rol-permissies/{roleId}/{permissionId}/edit', [RolePermissionController::class, 'edit'])->name('role-permissions.edit');
    Route::put('rol-permissies/{roleId}/{permissionId}', [RolePermissionController::class, 'update'])->name('role-permissions.update');
    Route::delete('rol-permissies/{roleId}/{permissionId}', [RolePermissionController::class, 'destroy'])->name('role-permissions.destroy');

    // 4. Rol koppelen aan Gebruiker (model_has_roles)
    Route::get('gebruiker-rollen', [UserRoleController::class, 'index'])->name('user-roles.index');
    Route::post('gebruiker-rollen', [UserRoleController::class, 'store'])->name('user-roles.store');
    Route::get('gebruiker-rollen/{userId}/{roleId}/edit', [UserRoleController::class, 'edit'])->name('user-roles.edit');
    Route::put('gebruiker-rollen/{userId}/{roleId}', [UserRoleController::class, 'update'])->name('user-roles.update');
    Route::delete('gebruiker-rollen/{userId}/{roleId}', [UserRoleController::class, 'destroy'])->name('user-roles.destroy');
});

require __DIR__.'/auth.php';