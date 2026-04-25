<?php

use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GamePostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC ROUTES =====
Route::get('/', [HomeController::class, 'index']);
Route::get('/developers', [DeveloperController::class, 'index']);

// ===== GAMES =====
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/create', [GameController::class, 'create'])->middleware(['auth', 'role:developer']);
Route::get('/games/{game_id}/edit', [GameController::class, 'edit'])->middleware(['auth', 'role:developer']);
Route::patch('/games/{game_id}', [GameController::class, 'update'])->middleware(['auth', 'role:developer']);
Route::delete('/games/{game_id}', [GameController::class, 'destroy'])->middleware(['auth', 'role:developer']);
Route::get('/games/{game_id}', [GameController::class, 'show']);
Route::post('/games/{game_id}/follow', [GameController::class, 'follow'])->middleware('auth');
Route::post('/games/store', [GameController::class, 'store'])->middleware(['auth', 'role:developer']);

// ===== GAME POSTS =====
Route::get('/games/{game_id}/posts/create', [GamePostController::class, 'create'])->middleware(['auth', 'role:developer']);
Route::post('/games/{game_id}/posts/store', [GamePostController::class, 'store'])->middleware(['auth', 'role:developer']);
Route::get('/games/{game_id}/posts/{post_id}', [GamePostController::class, 'show']);
Route::post('/games/{game_id}/posts/{post_id}/like', [GamePostController::class, 'like'])->middleware('auth');
Route::post('/games/{game_id}/posts/{post_id}/comments/store', [GamePostController::class, 'storeComment'])->middleware('auth');

// ===== USERS =====
Route::get('/users/{user_id}', [UserController::class, 'show']);
Route::post('/users/{user_id}/follow', [UserController::class, 'follow'])->middleware('auth');
Route::post('/users/{user_id}/rate', [UserController::class, 'rate'])->middleware('auth');

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

//email debug:
Route::get('/email-preview', function () {
    $user = App\Models\User::first();
    return new App\Mail\WelcomeMail($user);
});

require __DIR__.'/auth.php';