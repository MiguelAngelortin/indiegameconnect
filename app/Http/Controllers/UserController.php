<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\User;
use App\Models\UserFollow;
use App\Models\UserRating;

class UserController extends Controller
{
    /**
     * Muestra el perfil público de un usuario.
     * Si el usuario es developer o admin, carga además sus juegos publicados,
     * seguidores, porcentaje de valoraciones positivas y el estado de
     * follow y rating del usuario logueado respecto a ese perfil.
     *
     * @param  int  $user_id
     * @return \Illuminate\View\View
     */
    public function show($user_id)
    {
        $user = User::findOrFail($user_id);

        // Valores por defecto para usuarios normales — se sobreescriben si es developer/admin
        $games                  = null;
        $followersCount         = 0;
        $ratingPercent          = null;
        $isFollowing            = false;
        $userRating             = null;

        // Juegos seguidos por este usuario — limitado a 6 para la vista previa del perfil
        $followedGames          = $user->followedGames()->with('genres')->take(6)->get();
        $followedGamesCount     = $user->followedGames()->count(); // Total para el "View all"

        // Developers que sigue este usuario — limitado a 6 para la vista previa
        $followedDevelopers      = $user->following()->with('developer')->take(6)->get();
        $followedDevelopersCount = $user->following()->count(); // Total para el "View all"

        // Bloque exclusivo para developers y admins
        if ($user->role === 'developer' || $user->role === 'admin') {

            // Juegos publicados por este developer, paginados de 6 en 6
            $games = Game::with('genres')->where('user_id', $user->id)->paginate(6);

            // Número de usuarios que siguen a este developer
            $followersCount = $user->follows()->count();

            // Cálculo del porcentaje de valoraciones positivas (rating = 1)
            $ratingsPositive = $user->ratings()->where('rating', 1)->count();
            $ratingsTotal    = $user->ratings()->count();

            // Si no tiene valoraciones se devuelve null para no mostrar la barra en la vista
            $ratingPercent = $ratingsTotal > 0
                ? round(($ratingsPositive / $ratingsTotal) * 100)
                : null;

            // Comprueba si el usuario logueado sigue a este developer
            // Si no está logueado devuelve false sin consultar la BD
            $isFollowing = auth()->check()
                ? UserFollow::where('user_id', auth()->user()->id)
                            ->where('developer_id', $user->id)
                            ->exists()
                : false;

            // Obtiene la valoración que el usuario logueado dio a este developer (1 o -1)
            // Si no está logueado devuelve null sin consultar la BD
            $userRating = auth()->check()
                ? UserRating::where('user_id', auth()->user()->id)
                            ->where('developer_id', $user->id)
                            ->first()
                : null;
        }

        return view('users.show', compact(
            'user', 'games', 'followersCount', 'ratingPercent',
            'isFollowing', 'userRating', 'followedGames', 'followedGamesCount',
            'followedDevelopers', 'followedDevelopersCount'
        ));
    }

    /**
     * Alterna el estado de seguimiento de un developer para el usuario logueado.
     * Si ya lo sigue lo deja de seguir, si no lo sigue crea el follow.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow($user_id)
    {
        $follow = UserFollow::where('user_id', auth()->user()->id)
            ->where('developer_id', $user_id)
            ->first();

        if ($follow) {
            // Ya existe el follow — lo elimina (unfollow)
            $follow->delete();
        } else {
            // No existe el follow — lo crea
            UserFollow::create([
                'user_id'      => auth()->user()->id,
                'developer_id' => $user_id,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Guarda o actualiza la valoración del usuario logueado sobre un developer.
     * Solo acepta dos valores: 1 (positivo) o -1 (negativo).
     * Si ya existe una valoración previa la sobreescribe con updateOrCreate().
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rate(Request $request, $user_id)
    {
        $request->validate([
            // Solo se permiten los valores 1 (positivo) y -1 (negativo)
            'rating' => ['required', 'in:1,-1'],
        ]);

        // updateOrCreate busca por user_id + developer_id:
        // si existe, actualiza el rating; si no existe, crea el registro
        UserRating::updateOrCreate(
            ['user_id'      => auth()->user()->id, 'developer_id' => $user_id],
            ['rating'       => $request->rating]
        );

        return redirect()->back();
    }
}