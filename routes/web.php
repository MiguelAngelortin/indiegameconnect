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

// Feed requiere autenticación — el usuario necesita juegos seguidos para ver contenido
Route::get('/feed', [FeedController::class, 'index'])->middleware('auth');

// =========================================================
// JUEGOS
// Lectura pública — escritura protegida por auth + role:developer
// throttle:3,1440 = máximo 3 juegos creados por día por usuario
// =========================================================

Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{game_id}', [GameController::class, 'show']);

Route::get('/games/create', [GameController::class, 'create'])
    ->middleware(['auth', 'role:developer']);
Route::post('/games/store', [GameController::class, 'store'])
    ->middleware(['auth', 'role:developer', 'throttle:3,1440']);
Route::get('/games/{game_id}/edit', [GameController::class, 'edit'])
    ->middleware(['auth', 'role:developer']);
Route::patch('/games/{game_id}', [GameController::class, 'update'])
    ->middleware(['auth', 'role:developer']);
Route::delete('/games/{game_id}', [GameController::class, 'destroy'])
    ->middleware(['auth', 'role:developer']);

// Follow/unfollow de un juego — cualquier usuario autenticado
Route::post('/games/{game_id}/follow', [GameController::class, 'follow'])
    ->middleware('auth');

// =========================================================
// POSTS DEL DEVLOG
// Lectura y likes públicos — creación protegida por role:developer
// throttle:20,60  = máximo 20 posts por hora
// throttle:30,60  = máximo 30 comentarios por hora
// =========================================================

Route::get('/games/{game_id}/posts/{post_id}', [GamePostController::class, 'show']);

Route::get('/games/{game_id}/posts/create', [GamePostController::class, 'create'])
    ->middleware(['auth', 'role:developer']);
Route::post('/games/{game_id}/posts/store', [GamePostController::class, 'store'])
    ->middleware(['auth', 'role:developer', 'throttle:20,60']);
Route::get('/games/{game_id}/posts/{post_id}/edit', [GamePostController::class, 'edit'])
    ->middleware(['auth', 'role:developer']);
Route::patch('/games/{game_id}/posts/{post_id}', [GamePostController::class, 'update'])
    ->middleware(['auth', 'role:developer']);
Route::delete('/games/{game_id}/posts/{post_id}', [GamePostController::class, 'destroy'])
    ->middleware(['auth', 'role:developer']);

// Like — cualquier usuario autenticado
Route::post('/games/{game_id}/posts/{post_id}/like', [GamePostController::class, 'like'])
    ->middleware('auth');

// Comentarios — cualquier usuario autenticado con throttle
Route::post('/games/{game_id}/posts/{post_id}/comments/store', [GamePostController::class, 'storeComment'])
    ->middleware(['auth', 'throttle:30,60']);

// Borrar comentario — autor del comentario o admin (comprobado en el controlador)
Route::delete('/games/{game_id}/posts/{post_id}/comments/{comment_id}', [GamePostController::class, 'destroyComment'])
    ->middleware('auth');

// =========================================================
// USUARIOS
// Perfil público visible para todos
// Follow y rate requieren autenticación
// =========================================================

Route::get('/users/{user_id}', [UserController::class, 'show']);
Route::post('/users/{user_id}/follow', [UserController::class, 'follow'])->middleware('auth');
Route::post('/users/{user_id}/rate', [UserController::class, 'rate'])->middleware('auth');

// =========================================================
// DASHBOARD
// Vista por defecto de Breeze — requiere auth y email verificado
// =========================================================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// =========================================================
// PERFIL
// Edición, actualización y borrado de cuenta del usuario logueado
// =========================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================
// CONTACTO
// Formulario accesible para cualquier visitante
// =========================================================

Route::get('/contact', [ContactController::class, 'create']);
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// =========================================================
// PÁGINAS ESTÁTICAS
// Sin controlador — devuelven la vista directamente
// =========================================================

Route::get('/legal', function () {
    return view('legal');
});

Route::get('/help', function () {
    return view('help');
});

// =========================================================
// PANEL DE ADMINISTRACIÓN
// prefix('admin') añade /admin/ a todas las rutas del grupo
// Protegido globalmente con auth + role:admin
// =========================================================

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    // Panel principal con estadísticas
    Route::get('/', [AdminController::class, 'index']);

    // Gestión de usuarios — CRUD completo + ban/unban
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/users/{user_id}/edit', [AdminController::class, 'editUser']);
    Route::patch('/users/{user_id}', [AdminController::class, 'updateUser']);
    Route::post('/users/{user_id}/ban', [AdminController::class, 'banUser']);
    Route::delete('/users/{user_id}', [AdminController::class, 'destroyUser']);

    // Gestión de juegos — listado y borrado
    Route::get('/games', [AdminController::class, 'games']);
    Route::delete('/games/{game_id}', [AdminController::class, 'destroyGame']);

    // Gestión de posts — listado y borrado
    Route::get('/posts', [AdminController::class, 'posts']);
    Route::delete('/posts/{post_id}', [AdminController::class, 'destroyPost']);

    // Informe PDF con estadísticas de la plataforma
    Route::get('/report', [AdminController::class, 'report']);

    // Exportación e importación de BD
    Route::get('/export-database', [AdminController::class, 'exportDatabase']);
    Route::post('/import-database', [AdminController::class, 'importDatabase'])->name('admin.import-database');
});

// =========================================================
// SELECTOR DE IDIOMA
// Guarda el locale elegido en sesión y redirige a la página anterior
// Solo acepta 'en' y 'es' — cualquier otro valor se ignora
// =========================================================

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Carga las rutas de autenticación generadas por Breeze (login, register, etc.)
require __DIR__ . '/auth.php';
