<?php

use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GamePostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\AdminController;

// =========================================================
// RUTAS PÚBLICAS — accesibles para cualquier visitante
// =========================================================

Route::get('/', [HomeController::class, 'index']);
Route::get('/developers', [DeveloperController::class, 'index']);

Route::get('/feed', [FeedController::class, 'index'])->middleware('auth');

// =========================================================
// JUEGOS
// =========================================================

Route::get('/games', [GameController::class, 'index']);
Route::get('/games/create', [GameController::class, 'create'])
    ->middleware(['auth', 'role:developer']);
Route::post('/games/store', [GameController::class, 'store'])
    ->middleware(['auth', 'role:developer', 'throttle:3,1440']);
Route::get('/games/{game_id}', [GameController::class, 'show']);
Route::get('/games/{game_id}/edit', [GameController::class, 'edit'])
    ->middleware(['auth', 'role:developer']);
Route::patch('/games/{game_id}', [GameController::class, 'update'])
    ->middleware(['auth', 'role:developer']);
Route::delete('/games/{game_id}', [GameController::class, 'destroy'])
    ->middleware(['auth', 'role:developer']);
Route::post('/games/{game_id}/follow', [GameController::class, 'follow'])
    ->middleware('auth');

// =========================================================
// POSTS DEL DEVLOG
// =========================================================

Route::get('/games/{game_id}/posts/create', [GamePostController::class, 'create'])
    ->middleware(['auth', 'role:developer']);
Route::post('/games/{game_id}/posts/store', [GamePostController::class, 'store'])
    ->middleware(['auth', 'role:developer', 'throttle:20,60']);
Route::get('/games/{game_id}/posts/{post_id}', [GamePostController::class, 'show']);
Route::get('/games/{game_id}/posts/{post_id}/edit', [GamePostController::class, 'edit'])
    ->middleware(['auth', 'role:developer']);
Route::patch('/games/{game_id}/posts/{post_id}', [GamePostController::class, 'update'])
    ->middleware(['auth', 'role:developer']);
Route::delete('/games/{game_id}/posts/{post_id}', [GamePostController::class, 'destroy'])
    ->middleware(['auth', 'role:developer']);
Route::post('/games/{game_id}/posts/{post_id}/like', [GamePostController::class, 'like'])
    ->middleware('auth');
Route::post('/games/{game_id}/posts/{post_id}/comments/store', [GamePostController::class, 'storeComment'])
    ->middleware(['auth', 'throttle:30,60']);
Route::delete('/games/{game_id}/posts/{post_id}/comments/{comment_id}', [GamePostController::class, 'destroyComment'])
    ->middleware('auth');

// =========================================================
// USUARIOS
// =========================================================

Route::get('/users/{user_id}', [UserController::class, 'show']);
Route::post('/users/{user_id}/follow', [UserController::class, 'follow'])->middleware('auth');
Route::post('/users/{user_id}/rate', [UserController::class, 'rate'])->middleware('auth');

// =========================================================
// DASHBOARD
// =========================================================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// =========================================================
// PERFIL
// =========================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================
// CONTACTO
// =========================================================

Route::get('/contact', [ContactController::class, 'create']);
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// =========================================================
// PÁGINAS ESTÁTICAS
// =========================================================

Route::get('/legal', function () {
    return view('legal');
});

Route::get('/help', function () {
    return view('help');
});

// =========================================================
// PANEL DE ADMINISTRACIÓN
// =========================================================

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/users/{user_id}/edit', [AdminController::class, 'editUser']);
    Route::patch('/users/{user_id}', [AdminController::class, 'updateUser']);
    Route::post('/users/{user_id}/ban', [AdminController::class, 'banUser']);
    Route::delete('/users/{user_id}', [AdminController::class, 'destroyUser']);
    Route::get('/games', [AdminController::class, 'games']);
    Route::delete('/games/{game_id}', [AdminController::class, 'destroyGame']);
    Route::get('/posts', [AdminController::class, 'posts']);
    Route::delete('/posts/{post_id}', [AdminController::class, 'destroyPost']);
    Route::get('/report', [AdminController::class, 'report']);
    Route::get('/export-database', [AdminController::class, 'exportDatabase']);
    Route::post('/import-database', [AdminController::class, 'importDatabase'])->name('admin.import-database');
});

// =========================================================
// SELECTOR DE IDIOMA
// =========================================================

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

require __DIR__ . '/auth.php';