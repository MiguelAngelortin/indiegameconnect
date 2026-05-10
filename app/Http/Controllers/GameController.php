<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameFollow;
use App\Models\Genre;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class GameController extends Controller
{
    /**
     * Sube una imagen a Cloudinary y devuelve la URL segura (HTTPS) del archivo subido.
     * Las credenciales se cargan desde las variables de entorno definidas en .env
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string  URL segura de la imagen en Cloudinary
     */
    private function uploadToCloudinary($file): string
    {
        // Instancia Cloudinary con las credenciales del proyecto definidas en .env
        $cloudinary = new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ]
            ])
        );

        // getRealPath() devuelve la ruta temporal del archivo subido en el servidor
        $uploaded = $cloudinary->uploadApi()->upload($file->getRealPath());

        // Devuelve la URL HTTPS permanente donde quedará alojada la imagen
        return $uploaded['secure_url'];
    }

    /**
     * Muestra el listado de juegos con filtros opcionales por título, género,
     * estado de desarrollo y juegos seguidos por un usuario concreto.
     * Paginado en bloques de 12 resultados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $genres = Genre::all(); // Necesarios para rellenar el selector de filtro por género

        $games = Game::with(['genres', 'user'])
            ->when($request->search, function ($query) use ($request) {
                // Búsqueda parcial por título
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->genre, function ($query) use ($request) {
                // Filtra juegos que tengan el género seleccionado en su relación many-to-many
                $query->whereHas('genres', function ($q) use ($request) {
                    $q->where('genres.id', $request->genre);
                });
            })
            ->when($request->status, function ($query) use ($request) {
                // Filtra por estado de desarrollo: alpha, beta, release o cancelled
                $query->where('status', $request->status);
            })
            ->when($request->followed_by, function ($query) use ($request) {
                // Filtra juegos seguidos por un usuario concreto — usado en el "View all" del perfil
                $query->whereHas('followers', function ($q) use ($request) {
                    $q->where('user_id', $request->followed_by);
                });
            })
            ->paginate(12);

        return view('games', compact('games', 'genres'));
    }

    /**
     * Muestra la ficha completa de un juego con su información y el devlog
     * paginado con los posts del desarrollador.
     *
     * @param  int  $game_id
     * @return \Illuminate\View\View
     */
    public function show($game_id)
    {
        // Carga el juego con su developer y géneros en una sola query
        $game = Game::with(['user', 'genres'])->findOrFail($game_id);

        // Posts del devlog ordenados del más reciente al más antiguo, paginados de 5 en 5
        $posts = $game->gamePosts()->orderBy('created_at', 'desc')->paginate(5);

        return view('games.show', compact('game', 'posts'));
    }

    /**
     * Muestra el formulario de creación de un nuevo juego.
     * Solo accesible para usuarios con rol developer o admin (middleware en rutas).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $genres = Genre::all(); // Necesarios para mostrar las pills de géneros en el formulario
        return view('games.create', compact('genres'));
    }

    /**
     * Valida, sube la imagen si existe y guarda un nuevo juego en la base de datos.
     * Asocia los géneros seleccionados mediante la tabla intermedia game_genre.
     * Solo accesible para usuarios con rol developer o admin (middleware en rutas).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'genres'       => ['required', 'array', 'min:1'], // Al menos un género obligatorio
            'status'       => ['required', 'in:alpha,beta,release,cancelled'],
            'engine'       => ['required', 'in:Unity,Unreal,Godot,GameMaker,Other'],
            'publisher'    => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'cover_image'  => ['nullable', 'image', 'max:4096'], // Máximo 4MB
            'download_url' => ['nullable', 'string'],
            'version'      => ['nullable', 'string'],
        ]);

        // Si se sube portada, se envía a Cloudinary y se guarda la URL; si no, queda null
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $this->uploadToCloudinary($request->file('cover_image'));
        }

        $game = Game::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'user_id'      => auth()->user()->id, // El juego se asocia al usuario logueado
            'status'       => $request->status,
            'engine'       => $request->engine,
            'publisher'    => $request->publisher,
            'release_date' => $request->release_date,
            'cover_image'  => $coverPath,
            'download_url' => $request->download_url,
            'version'      => $request->version,
        ]);

        // attach() inserta los géneros en la tabla intermedia game_genre
        $game->genres()->attach($request->genres);

        return redirect('/games/' . $game->id);
    }

    /**
     * Muestra el formulario de edición de un juego con sus datos precargados.
     * Solo el propietario del juego puede acceder a editarlo.
     *
     * @param  int  $game_id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($game_id)
    {
        $game   = Game::with('genres')->findOrFail($game_id);
        $genres = Genre::all();

        // Si el usuario logueado no es el dueño del juego, lo redirige a la ficha
        if (auth()->user()->id !== $game->user_id) {
            return redirect('/games/' . $game_id);
        }

        return view('games.edit', compact('game', 'genres'));
    }

    /**
     * Valida y guarda los cambios sobre un juego existente.
     * Si se sube nueva portada, reemplaza la URL anterior con la nueva de Cloudinary.
     * Sincroniza los géneros seleccionados eliminando los anteriores y añadiendo los nuevos.
     * Solo el propietario del juego puede actualizarlo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $game_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $game_id)
    {
        $game = Game::findOrFail($game_id);

        // Verificación de propiedad antes de procesar nada
        if (auth()->user()->id !== $game->user_id) {
            return redirect('/games/' . $game_id);
        }

        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'genres'       => ['required', 'array', 'min:1'],
            'status'       => ['required', 'in:alpha,beta,release,cancelled'],
            'engine'       => ['required', 'in:Unity,Unreal,Godot,GameMaker,Other'],
            'publisher'    => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'cover_image'  => ['nullable', 'image', 'max:4096'],
            'download_url' => ['nullable', 'string'],
            'version'      => ['nullable', 'string'],
        ]);

        // except() excluye los campos que no van directamente a la BD
        $data = $request->except('genres', '_token', '_method', 'cover_image');

        // Si hay nueva portada se sube a Cloudinary y se sobreescribe la URL en $data
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->uploadToCloudinary($request->file('cover_image'));
        }

        $game->update($data);

        // sync() reemplaza los géneros anteriores por los nuevos seleccionados en el formulario
        $game->genres()->sync($request->genres);

        return redirect('/games/' . $game_id);
    }

    /**
     * Elimina un juego permanentemente de la base de datos.
     * Solo el propietario del juego puede eliminarlo.
     *
     * @param  int  $game_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($game_id)
    {
        $game = Game::findOrFail($game_id);

        // Verificación de propiedad antes de eliminar
        if (auth()->user()->id !== $game->user_id) {
            return redirect('/games/' . $game_id);
        }

        $game->delete();
        return redirect('/games');
    }

    /**
     * Alterna el estado de seguimiento de un juego para el usuario logueado.
     * Si ya lo sigue, lo deja de seguir. Si no lo sigue, crea el follow.
     *
     * @param  int  $game_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow($game_id)
    {
        $follow = GameFollow::where('user_id', auth()->user()->id)
            ->where('game_id', $game_id)
            ->first();

        if ($follow) {
            // Ya existe el follow — lo elimina (unfollow)
            $follow->delete();
        } else {
            // No existe el follow — lo crea
            GameFollow::create([
                'user_id' => auth()->user()->id,
                'game_id' => $game_id,
            ]);
        }

        // Redirige a la página desde donde se hizo la acción
        return redirect()->back();
    }
}