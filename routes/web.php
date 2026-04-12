<?php

use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GamePostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC ROUTES =====
Route::get('/', function () {
    return view('home');
});
Route::get('/developers', [DeveloperController::class, 'index']);

// ===== GAMES =====
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{game_id}', [GameController::class, 'show']);
Route::get('/games/create', [GameController::class, 'create'])->middleware(['auth', 'role:developer']);
Route::post('/games/store', [GameController::class, 'store'])->middleware(['auth', 'role:developer']);

// ===== GAME POSTS =====
Route::get('/games/{game_id}/posts/{post_id}', [GamePostController::class, 'show']);
Route::get('/games/{game_id}/posts/create', [GamePostController::class, 'create'])->middleware(['auth', 'role:developer']);
Route::post('/games/{game_id}/posts/store', [GamePostController::class, 'store'])->middleware(['auth', 'role:developer']);
Route::post('/games/{game_id}/posts/{post_id}/like', [GamePostController::class, 'like'])->middleware('auth');
Route::post('/games/{game_id}/posts/{post_id}/comments/store', [GamePostController::class, 'storeComment'])->middleware('auth');

// ===== USERS =====
Route::get('/users/{user_id}', [UserController::class, 'show']);

// ===== DASHBOARD =====
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== PROFILE (auth) =====
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';