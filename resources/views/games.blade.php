{{-- Extiende el layout maestro que incluye navbar, footer y assets globales --}}
@extends('layouts.app')

@section('title', 'Games')

@section('content')
<div class="container mt-4">

    {{-- ===== BUSCADOR Y FILTROS ===== --}}
    {{-- GET en lugar de POST porque los filtros forman parte de la URL y son compartibles --}}
    <form method="GET" action="/games" class="row g-2 mb-4">

        {{-- Búsqueda por título --}}
        <div class="col-12 col-md-5">
            {{-- request('search') mantiene el valor introducido visible tras filtrar --}}
            <input type="text" name="search" class="form-control"
                   placeholder="{{ __('games.search_placeholder') }}"
                   value="{{ request('search') }}">
        </div>

        {{-- Filtro por género — se puebla con los géneros de la BD pasados desde GameController --}}
        <div class="col-6 col-md-3">
            <select name="genre" class="form-select">
                <option value="">{{ __('games.all_genres') }}</option>
                @foreach ($genres as $genre)
                    {{-- selected si el género actual coincide con el filtro aplicado --}}
                    <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filtro por estado de desarrollo --}}
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">{{ __('games.all_status') }}</option>
                {{-- selected en cada opción si coincide con el filtro aplicado en la URL --}}
                <option value="alpha"     {{ request('status') == 'alpha'     ? 'selected' : '' }}>Alpha</option>
                <option value="beta"      {{ request('status') == 'beta'      ? 'selected' : '' }}>Beta</option>
                <option value="release"   {{ request('status') == 'release'   ? 'selected' : '' }}>Release</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="col-12 col-md-2">
            <button type="submit" class="btn-register w-100">{{ __('games.search_btn') }}</button>
        </div>

    </form>

    {{-- ===== GRID DE JUEGOS ===== --}}
    <div class="row g-3">
        {{-- @forelse muestra el grid si hay juegos, o el mensaje vacío si no hay resultados --}}
        @forelse ($games as $game)
            <div class="col-6 col-md-4 col-lg-2 d-flex">
                <a href="/games/{{ $game->id }}" class="text-decoration-none w-100">
                    <div class="game-card">

                        <div class="game-card-img-container">
                            {{-- Si el juego tiene portada en Cloudinary se usa su URL directamente --}}
                            {{-- Si no tiene portada se muestra la imagen por defecto local --}}
                            <img src="{{ $game->cover_image ? asset($game->cover_image) : asset('img/default_cover.jpg') }}"
                                 alt="{{ $game->title }}">
                        </div>

                        <div class="game-card-body">
                            <h6 class="game-title">{{ $game->title }}</h6>
                            <div>
                                {{-- Se muestran como máximo 4 géneros para no desbordar la card --}}
                                @foreach ($game->genres->take(4) as $genre)
                                    <span class="genre-tag">{{ $genre->name }}</span>
                                @endforeach
                                {{-- Si hay más de 4 géneros se muestra un badge con el número restante --}}
                                @if($game->genres->count() > 4)
                                    <span class="genre-tag">+{{ $game->genres->count() - 4 }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </a>
            </div>
        @empty
            <p>{{ __('games.no_games_found') }}</p>
        @endforelse
    </div>

    {{-- Paginación con Bootstrap 5 centrada --}}
    {{-- Sin appends() porque links('pagination::bootstrap-5') conserva los parámetros GET automáticamente --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $games->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection