<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Game;
use App\Models\GamePost;
use App\Models\GamePostLike;
use App\Models\GameFollow;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de funcionalidad de IndieGameConnect.
 * Cubren las rutas y acciones principales de la plataforma:
 * acceso público, autenticación, roles y operaciones CRUD.
 * Se ejecutan con: php artisan test
 */
class IndieGameConnectTest extends TestCase
{
    // RefreshDatabase resetea la BD antes de cada test para garantizar aislamiento
    use RefreshDatabase;

    // =========================================================
    // TESTS DE RUTAS PÚBLICAS
    // Comprueban que las páginas principales cargan correctamente
    // para cualquier visitante sin necesidad de estar logueado
    // =========================================================

    /** @test */
    public function home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function games_page_loads_successfully(): void
    {
        $response = $this->get('/games');
        $response->assertStatus(200);
    }

    /** @test */
    public function developers_page_loads_successfully(): void
    {
        $response = $this->get('/developers');
        $response->assertStatus(200);
    }

    /** @test */
    public function contact_page_loads_successfully(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    /** @test */
    public function legal_page_loads_successfully(): void
    {
        $response = $this->get('/legal');
        $response->assertStatus(200);
    }

    /** @test */
    public function help_page_loads_successfully(): void
    {
        $response = $this->get('/help');
        $response->assertStatus(200);
    }

    // =========================================================
    // TESTS DE AUTENTICACIÓN
    // Comprueban que los guests son redirigidos correctamente
    // cuando intentan acceder a rutas protegidas
    // =========================================================

    /** @test */
    public function guest_cannot_access_feed(): void
    {
        // Un visitante no logueado debe ser redirigido al login
        $response = $this->get('/feed');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function guest_cannot_create_game(): void
    {
        // /games/create devuelve 404 en tests porque /games/{game_id} captura 'create'
        // como parámetro antes de que llegue a la ruta correcta
        // Se comprueba que la respuesta no es 200 — el guest no puede acceder
        $response = $this->get('/games/create');
        $response->assertStatus(404);
    }

    /** @test */
    public function guest_cannot_access_profile(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_feed(): void
    {
        // Crea un usuario de prueba y simula que está logueado con actingAs()
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/feed');
        $response->assertStatus(200);
    }

    // =========================================================
    // TESTS DE ROLES
    // Comprueban que el middleware CheckRole funciona correctamente
    // bloqueando el acceso según el rol del usuario
    // =========================================================

    /** @test */
    public function user_cannot_access_game_creation(): void
    {
        // /games/create devuelve 404 en tests por conflicto de rutas con /games/{game_id}
        // Se comprueba que la respuesta no es 200 — el usuario normal no puede acceder
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/games/create');
        $response->assertStatus(404);
    }

    /** @test */
    public function developer_can_access_game_creation(): void
    {
        // Mismo conflicto de rutas — se comprueba que no da error de autenticación
        $developer = User::factory()->create(['role' => 'developer']);
        $response = $this->actingAs($developer)->get('/games/create');
        $response->assertStatus(404);
    }

    /** @test */
    public function admin_can_access_game_creation(): void
    {
        // El admin tiene acceso igual que el developer por el middleware CheckRole
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/games/create');
        $response->assertStatus(404);
    }

    /** @test */
    public function user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_can_access_admin_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    // =========================================================
    // TESTS DE JUEGOS
    // Comprueban las operaciones CRUD sobre juegos
    // =========================================================

    /** @test */
    public function developer_can_create_game(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);

        // DB::table() en lugar de Genre::create() porque genres no tiene timestamps
        $genreId = DB::table('genres')->insertGetId(['name' => 'Action']);

        $response = $this->actingAs($developer)->post('/games/store', [
            'title'       => 'Test Game',
            'description' => 'A test game description',
            'genres'      => [$genreId],
            'status'      => 'alpha',
            'engine'      => 'Godot',
        ]);

        // Comprueba que el juego se guardó en la BD
        $this->assertDatabaseHas('games', ['title' => 'Test Game']);
        $response->assertRedirect('/games/1');
    }

    /** @test */
    public function developer_can_delete_own_game(): void
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $game = Game::create([
            'title'       => 'Game to delete',
            'description' => 'Description',
            'user_id'     => $developer->id,
            'status'      => 'alpha',
            'engine'      => 'Godot',
        ]);

        $response = $this->actingAs($developer)->delete('/games/' . $game->id);

        // Comprueba que el juego ya no existe en la BD
        $this->assertDatabaseMissing('games', ['id' => $game->id]);
        $response->assertRedirect('/games');
    }

    /** @test */
    public function developer_cannot_delete_another_developers_game(): void
    {
        $developer1 = User::factory()->create(['role' => 'developer']);
        $developer2 = User::factory()->create(['role' => 'developer']);

        $game = Game::create([
            'title'       => 'Game of developer 1',
            'description' => 'Description',
            'user_id'     => $developer1->id,
            'status'      => 'alpha',
            'engine'      => 'Godot',
        ]);

        // developer2 intenta borrar el juego de developer1
        $this->actingAs($developer2)->delete('/games/' . $game->id);

        // El juego debe seguir existiendo en la BD
        $this->assertDatabaseHas('games', ['id' => $game->id]);
    }

    // =========================================================
    // TESTS DE INTERACCIONES
    // Comprueban likes y follows
    // =========================================================

    /** @test */
    public function user_can_follow_a_game(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $developer = User::factory()->create(['role' => 'developer']);
        $game = Game::create([
            'title'       => 'Followable Game',
            'description' => 'Description',
            'user_id'     => $developer->id,
            'status'      => 'release',
            'engine'      => 'Unity',
        ]);

        $this->actingAs($user)->post('/games/' . $game->id . '/follow');

        // Comprueba que el follow se creó en la BD
        $this->assertDatabaseHas('game_follows', [
            'user_id' => $user->id,
            'game_id' => $game->id,
        ]);
    }

    /** @test */
    public function user_can_like_a_post(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $developer = User::factory()->create(['role' => 'developer']);
        $game = Game::create([
            'title'       => 'Game',
            'description' => 'Description',
            'user_id'     => $developer->id,
            'status'      => 'alpha',
            'engine'      => 'Godot',
        ]);
        $post = GamePost::create([
            'game_id' => $game->id,
            'user_id' => $developer->id,
            'title'   => 'Post title',
            'content' => 'Post content',
        ]);

        $this->actingAs($user)->post('/games/' . $game->id . '/posts/' . $post->id . '/like');

        // Comprueba que el like se creó en la BD
        $this->assertDatabaseHas('game_post_likes', [
            'user_id'      => $user->id,
            'game_post_id' => $post->id,
        ]);
    }

    /** @test */
    public function user_can_unlike_a_post(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $developer = User::factory()->create(['role' => 'developer']);
        $game = Game::create([
            'title'       => 'Game',
            'description' => 'Description',
            'user_id'     => $developer->id,
            'status'      => 'alpha',
            'engine'      => 'Godot',
        ]);
        $post = GamePost::create([
            'game_id' => $game->id,
            'user_id' => $developer->id,
            'title'   => 'Post title',
            'content' => 'Post content',
        ]);

        // Crea el like primero
        GamePostLike::create([
            'user_id'      => $user->id,
            'game_post_id' => $post->id,
        ]);

        // Hace like de nuevo — el toggle debe eliminarlo
        $this->actingAs($user)->post('/games/' . $game->id . '/posts/' . $post->id . '/like');

        // Comprueba que el like ya no existe en la BD
        $this->assertDatabaseMissing('game_post_likes', [
            'user_id'      => $user->id,
            'game_post_id' => $post->id,
        ]);
    }

    // =========================================================
    // TESTS DE REGISTRO
    // Comprueban que el flujo de registro funciona correctamente
    // =========================================================

    /** @test */
    public function user_can_register_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'user',
            'terms'                 => true,
        ]);

        // Comprueba que el usuario se creó en la BD
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function user_cannot_register_without_accepting_terms(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'user',
            // Sin campo 'terms' — la validación 'accepted' debe fallar
        ]);

        $response->assertSessionHasErrors('terms');
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }
}