@extends('layouts.app')
@section('title', 'Inicio')
@section('content')
    {{-- Hero --}}
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Discover your next great indie</h1>
                    <p class="text-center">The platform where indie developers share their games and players discover them before anyone else.</p>
                    <div class="text-center">
                        <a href="/games" class="btn-register">Let's discover games</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Functions --}}
    <section class="doubles">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="feature-card col-6">
                    <h2 class="section-title">Feel Lucky?</h2>
                    <p>Don't know what to play next? <br> Press the button and find a random game.</p>
                    @if($randomGame)
                        <a class="btn-register" href="/games/{{ $randomGame->id }}">Random Game</a>
                    @endif
                </div>
                <div class="feature-card col-6">
                    <h2 class="section-title">Join the community</h2>
                    <p>Support indie games and developers is easier if you are logged.</p>
                    @guest
                        <a class="btn-register" href="/register">Register</a>
                    @endguest
                    @auth
                        <a class="btn-register" href="/games">Explore Games</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Cards --}}
    <section class="cards mt-4">
        <div class="container">
            <div class="row g-4">
                {{-- Top Games --}}
                <div class="col-12 col-md-4">
                    <div class="home-section-card">
                        <h2 class="section-title mb-3">Top Games</h2>
                        <div class="d-flex flex-column">
                            @forelse($topGames as $game)
                                <a href="/games/{{ $game->id }}" class="text-decoration-none">
                                    <div class="home-list-item">
                                        <img src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                                        <span>{{ $game->title }}</span>
                                    </div>
                                </a>
                            @empty
                                <p>No games yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                {{-- Top Developers --}}
                <div class="col-12 col-md-4">
                    <div class="home-section-card">
                        <h2 class="section-title mb-3">Top Developers</h2>
                        <div class="d-flex flex-column">
                            @forelse($topDevelopers as $dev)
                                <a href="/users/{{ $dev->id }}" class="text-decoration-none">
                                    <div class="home-list-item">
                                        <img src="{{ $dev->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}" alt="{{ $dev->name }}">
                                        <span>{{ $dev->name }}</span>
                                    </div>
                                </a>
                            @empty
                                <p>No developers yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                {{-- In Development --}}
                <div class="col-12 col-md-4">
                    <div class="home-section-card">
                        <h2 class="section-title mb-3">In Development</h2>
                        <div class="d-flex flex-column">
                            @forelse($inDevelopment as $game)
                                <a href="/games/{{ $game->id }}" class="text-decoration-none">
                                    <div class="home-list-item">
                                        <img src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                                        <span>{{ $game->title }}</span>
                                    </div>
                                </a>
                            @empty
                                <p>No games in development.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection