<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DeveloperController extends Controller
{
    /**
     * Muestra la vista de desarrolladores con un buscador, un grid paginado
     * y un podio con los 3 mejores desarrolladores del último mes.
     * Si hay búsqueda activa, el grid muestra cualquier usuario por nombre.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Grid principal paginado — comportamiento distinto según si hay búsqueda o no
        $developers = User::where(function ($query) use ($request) {
                if ($request->search) {
                    // Con búsqueda: encuentra cualquier usuario por nombre
                    $query->where('name', 'like', '%' . $request->search . '%');
                } else {
                    // Sin búsqueda: muestra developers
                    $query->where('role', 'developer');
                }
            })
            ->withCount('follows') // Añade follows_count a cada usuario para mostrarlo en la card
            ->paginate(12);

        // Podio top 3 — solo developers, ordenados por puntuación del último mes
        $topDevelopers = User::where('role', 'developer')
            ->withCount(['follows' => function ($query) {
                // Solo cuenta los follows recibidos en los últimos 30 días
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->withCount(['ratings as positive_ratings' => function ($query) {
                // Cuenta solo las valoraciones positivas (rating = 1) del último mes
                $query->where('rating', 1)
                      ->where('created_at', '>=', now()->subDays(30));
            }])
            ->get()
            ->map(function ($dev) {
                // Fórmula de puntuación: los ratings positivos valen el doble que los follow
                $dev->score = ($dev->positive_ratings * 2) + $dev->follows_count;
                return $dev;
            })
            ->sortByDesc('score') // Ordena la colección en PHP por la puntuación calculada
            ->take(3)             // Toma solo los 3 primeros para el podio
            ->values();           // Reindexa el array para que empiece en 0 (necesario para la vista)

        return view("developers", compact('developers', 'topDevelopers'));
    }
}