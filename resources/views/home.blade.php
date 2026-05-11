{{-- Extiende el layout maestro que incluye navbar, footer y assets globales --}}
@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

    {{-- ===== HERO ===== --}}
    {{-- Sección de bienvenida con título, subtítulo y botón de acceso a juegos --}}
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">{{ __('home.hero_title') }}</h1>
                    <p class="text-center">{{ __('home.hero_text') }}</p>
                    <div class="text-center">
                        <a href="/games" class="btn-register">{{ __('home.lets_discover') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECCIÓN DOUBLES ===== --}}
    {{-- Franja de dos columnas con contenido distinto según si el usuario está logueado o no --}}
    <section class="doubles w-100">
        <div class="row align-items-center g-0 mx-0">

            {{-- Columna izquierda — cambia contenido según estado de autenticación --}}
            <div class="feature-card col-12 col-md-6">

                {{-- @guest muestra el bloque solo a visitantes no logueados --}}
                @guest
                    <h2 class="doubles-title">{{ __('home.join') }}</h2>
                @endguest

                {{-- @auth muestra el bloque solo a usuarios logueados --}}
                @auth
                    <h2 class="doubles-title">{{ __('home.support') }}</h2>
                @endauth

                @guest
                    {{-- Call to action para que el visitante se registre en la plataforma --}}
                    <p>{{ __('home.join_text') }}</p>
                    <a class="btn-register" href="/register">{{ __('nav.register') }}</a>
                @endguest

                @auth
                    {{-- Botones de donación a la plataforma — solo visibles para usuarios logueados --}}
                    <p>{{ __('home.support_text') }}</p>
                    <div class="d-flex gap-3 justify-content-center mt-3">
                        <a href="https://ko-fi.com/indiegameconnect" target="_blank" class="btn-donation">
                            <img src="{{ asset('img/kofi.png') }}" alt="Ko-fi">
                        </a>
                        <a href="https://www.paypal.com/paypalme/indiegameconnect" target="_blank" class="btn-donation">
                            <img src="{{ asset('img/paypal.png') }}" alt="PayPal">
                        </a>
                        <a href="https://www.patreon.com/c/IndieGameConnect" target="_blank" class="btn-donation">
                            <img src="{{ asset('img/patreon.png') }}" alt="Patreon">
                        </a>
                    </div>
                @endauth

            </div>

            {{-- Columna derecha — juego aleatorio, visible para todos los usuarios --}}
            <div class="feature-card col-12 col-md-6">
                <h2 class="doubles-title">{{ __('home.feel_lucky') }}</h2>
                <p>{{ __('home.feel_lucky_text') }}</p>
                {{-- Solo muestra el botón si existe al menos un juego en la BD --}}
                @if ($randomGame)
                    <a class="btn-register" href="/games/{{ $randomGame->id }}">{{ __('home.random_game') }}</a>
                @endif
            </div>

        </div>
    </section>

    {{-- ===== SECCIÓN CARDS ===== --}}
    {{-- Tres columnas con rankings y juegos en desarrollo, datos reales de la BD --}}
    <section class="cards mt-4">
        <div class="container">
            <div class="row g-4">

                {{-- Top 5 juegos más seguidos del último mes --}}
                <div class="col-12 col-md-4">
                    <div class="home-section-card">
                        <h2 class="section-title mb-3">{{ __('home.top_games') }}</h2>
                        <div class="d-flex flex-column">
                            @forelse($topGames as $game)
                                <a href="/games/{{ $game->id }}" class="text-decoration-none">
                                    <div class="home-list-item">
                                        <img src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                                        <span>{{ $game->title }}</span>
                                    </div>
                                </a>
                            @empty
                                {{-- Se muestra si no hay juegos con follows este mes --}}
                                <p>{{ __('home.no_games') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Top 5 developers más seguidos del último mes --}}
                <div class="col-12 col-md-4">
                    <div class="home-section-card">
                        <h2 class="section-title mb-3">{{ __('home.top_developers') }}</h2>
                        <div class="d-flex flex-column">
                            @forelse($topDevelopers as $dev)
                                <a href="/users/{{ $dev->id }}" class="text-decoration-none">
                                    <div class="home-list-item">
                                        {{-- Fallback a avatar genérico si el developer no tiene foto --}}
                                        <img src="{{ $dev->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                                            alt="{{ $dev->name }}">
                                        <span>{{ $dev->name }}</span>
                                    </div>
                                </a>
                            @empty
                                <p>{{ __('home.no_developers') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- 5 juegos más recientes en estado alpha o beta --}}
                <div class="col-12 col-md-4">
                    <div class="home-section-card">
                        <h2 class="section-title mb-3">{{ __('home.in_development') }}</h2>
                        <div class="d-flex flex-column">
                            @forelse($inDevelopment as $game)
                                <a href="/games/{{ $game->id }}" class="text-decoration-none">
                                    <div class="home-list-item">
                                        <img src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                                        <span>{{ $game->title }}</span>
                                    </div>
                                </a>
                            @empty
                                <p>{{ __('home.no_in_development') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection