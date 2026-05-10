<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePost;
use App\Models\GamePostLike;
use App\Models\GamePostComment;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class GamePostController extends Controller
{
    /**
     * Sube una imagen a Cloudinary y devuelve la URL segura (HTTPS) del archivo subido.
     * Método privado reutilizado en store() y update().
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string  URL segura de la imagen en Cloudinary
     */
    private function uploadToCloudinary($file): string
    {
        // Instancia Cloudinary con las credenciales definidas en .env
        $cloudinary = new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ]
            ])
        );

        // getRealPath() devuelve la ruta temporal del archivo en el servidor
        $uploaded = $cloudinary->uploadApi()->upload($file->getRealPath());

        // Devuelve la URL HTTPS permanente donde quedará alojada la imagen
        return $uploaded['secure_url'];
    }

    /**
     * Muestra el formulario para crear un nuevo post en el devlog de un juego.
     * Solo accesible para el developer propietario del juego (middleware en rutas).
     *
     * @param  int  $game_id
     * @return \Illuminate\View\View
     */
    public function create($game_id)
    {
        $game = Game::findOrFail($game_id);
        return view('games.posts.create', compact('game'));
    }

    /**
     * Valida, sube la imagen si existe y guarda un nuevo post en el devlog.
     * Asocia el post al juego y al usuario logueado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $game_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $game_id)
    {
        $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'content'   => ['required', 'string'],
            'image_url' => ['nullable', 'image', 'max:4096'], // Máximo 4MB
        ]);

        // Si se sube imagen se envía a Cloudinary y se guarda la URL; si no, queda null
        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $imagePath = $this->uploadToCloudinary($request->file('image_url'));
        }

        GamePost::create([
            'game_id'   => $game_id,
            'user_id'   => auth()->user()->id, // El post se asocia al usuario logueado
            'title'     => $request->title,
            'content'   => $request->content,
            'image_url' => $imagePath,
        ]);

        return redirect('/games/' . $game_id);
    }

    /**
     * Muestra la vista completa de un post con sus comentarios, respuestas anidadas
     * y el estado de like del usuario logueado.
     *
     * @param  int  $game_id
     * @param  int  $post_id
     * @return \Illuminate\View\View
     */
    public function show($game_id, $post_id)
    {
        $game = Game::findOrFail($game_id);
        $post = GamePost::findOrFail($post_id);

        // Carga solo los comentarios raíz (sin parent_id) con sus respuestas anidadas
        // whereNull('parent_id') excluye las replies para no mostrarlas duplicadas
        $comments = $post->comments()
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->with('replies') // Carga las respuestas de cada comentario en una sola query
            ->get();

        // Comprueba si el usuario logueado ya ha dado like a este post
        // Si no está logueado devuelve false directamente sin consultar la BD
        $userLiked = auth()->check()
            ? $post->likes()->where('user_id', auth()->user()->id)->exists()
            : false;

        return view('games.posts.show', compact('game', 'post', 'comments', 'userLiked'));
    }

    /**
     * Alterna el estado de like de un post para el usuario logueado.
     * Si ya existe el like lo elimina, si no existe lo crea.
     *
     * @param  int  $game_id
     * @param  int  $post_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like($game_id, $post_id)
    {
        $like = GamePostLike::where('game_post_id', $post_id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if ($like) {
            // Ya existe el like — lo elimina (unlike)
            $like->delete();
        } else {
            // No existe el like — lo crea
            GamePostLike::create([
                'game_post_id' => $post_id,
                'user_id'      => auth()->user()->id,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Valida y guarda un comentario o respuesta en un post del devlog.
     * Si viene parent_id es una respuesta a otro comentario, si no es un comentario raíz.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $game_id
     * @param  int  $post_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeComment(Request $request, $game_id, $post_id)
    {
        $request->validate([
            'content' => ['required', 'string'],
        ]);

        GamePostComment::create([
            'game_post_id' => $post_id,
            'user_id'      => auth()->user()->id,
            'content'      => $request->content,
            // Si viene parent_id es una reply; si no, null indica comentario raíz
            'parent_id'    => $request->parent_id ?? null,
        ]);

        return redirect()->back();
    }

    /**
     * Muestra el formulario de edición de un post con sus datos precargados.
     * Solo el autor del post puede acceder a editarlo.
     *
     * @param  int  $game_id
     * @param  int  $post_id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($game_id, $post_id)
    {
        $game = Game::findOrFail($game_id);
        $post = GamePost::findOrFail($post_id);

        // Si el usuario logueado no es el autor del post, lo redirige a la ficha del juego
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/games/' . $game_id);
        }

        return view('games.posts.edit', compact('game', 'post'));
    }

    /**
     * Valida y guarda los cambios sobre un post existente.
     * Si se sube nueva imagen, reemplaza la URL anterior con la nueva de Cloudinary.
     * Solo el autor del post puede actualizarlo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $game_id
     * @param  int  $post_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $game_id, $post_id)
    {
        $post = GamePost::findOrFail($post_id);

        // Verificación de propiedad antes de procesar nada
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/games/' . $game_id);
        }

        $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'content'   => ['required', 'string'],
            'image_url' => ['nullable', 'image', 'max:4096'],
        ]);

        // Solo se toman title y content — image_url se gestiona por separado
        $data = $request->only('title', 'content');

        // Si hay nueva imagen se sube a Cloudinary y se sobreescribe la URL en $data
        if ($request->hasFile('image_url')) {
            $data['image_url'] = $this->uploadToCloudinary($request->file('image_url'));
        }

        $post->update($data);
        return redirect('/games/' . $game_id . '/posts/' . $post_id);
    }

    /**
     * Elimina permanentemente un post del devlog.
     * Solo el autor del post puede eliminarlo.
     *
     * @param  int  $game_id
     * @param  int  $post_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($game_id, $post_id)
    {
        $post = GamePost::findOrFail($post_id);

        // Verificación de propiedad antes de eliminar
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/games/' . $game_id);
        }

        $post->delete();
        return redirect('/games/' . $game_id);
    }

    /**
     * Elimina un comentario o respuesta de un post.
     * Pueden eliminar comentarios: el autor del comentario o cualquier administrador.
     *
     * @param  int  $game_id
     * @param  int  $post_id
     * @param  int  $comment_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyComment($game_id, $post_id, $comment_id)
    {
        $comment = GamePostComment::findOrFail($comment_id);

        // Doble condición: solo puede borrar el autor del comentario o un admin
        if (auth()->user()->id !== $comment->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back();
        }

        $comment->delete();
        return redirect()->back();
    }
}