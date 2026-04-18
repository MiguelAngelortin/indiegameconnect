@extends('layouts.app')
@section('title', 'Show Games')
@section('content')
    <div class="container mt-4">
        <div class="row g-3 align-items-stretch justify-content-center">
            {{-- Imagen del juego --}}
            <div class="col-12 col-lg-game-img">
                <img class="game_img" src="{{ $game->cover_image }}" alt="game_img">
            </div>
            {{-- Info del juego --}}
            <div class="col-12 col-lg-6">
                <h2 class="mt-3 game-title">{{ $game->title }}</h2>
                <div class="mb-2">
                    @foreach ($game->genres as $genre)
                        <span class="genre-tag">{{ $genre->name }}</span>
                    @endforeach
                </div>
                <p>{{ $game->description }}</p>
                <div class="d-flex align-items-stretch gap-3 mt-3">
                    <div class="game-details-card">
                        <small class="game-details">Game details:</small><br>
                        <small>Engine: {{ $game->engine }}</small><br>
                        <small>Status: {{ $game->status }}</small><br>
                        <small>Version: {{ $game->version }}</small>
                    </div>
                    @if ($game->download_url)
                        <a href="{{ $game->download_url }}" target="_blank" class="btn-download d-flex align-items-center justify-content-center flex-fill">Download Game</a>
                    @endif
                </div>
            </div>
            {{-- Card developer --}}
            <div class="col-12 col-lg-2 d-flex">
                <div class="dev-card w-100 text-center">
                    <h5>{{ $game->user->name }}</h5>
                    @if ($game->user->profile_img)
                        <img src="{{ $game->user->profile_img }}" alt="profile_image" class="profile-img my-3">
                    @endif
                    <div class="mt-2">
                        <small>👥 {{ $game->user->follows()->count() }} followers</small>
                    </div>
                    <a href="/users/{{ $game->user->id }}" class="btn-register mt-3 d-inline-block">Ver perfil</a>
                </div>
            </div>
        </div>
        {{-- Devlog --}}
        <div class="row mt-4 justify-content-center">
            <div class="col-12 col-lg-8">
                @auth
                    @if (Auth::user()->id === $game->user_id)
                        <a href="/games/{{ $game->id }}/posts/create" class="btn-register mb-3">New Post</a>
                    @endif
                @endauth
                @forelse ($posts as $post)
                    <a href="/games/{{ $game->id }}/posts/{{ $post->id }}" class="text-decoration-none">
                        <div class="card post-card mb-3">
                            <div class="card-body">
                                <h5 class="game-title">{{ $post->title }}</h5>
                                <p>{{ $post->content }}</p>
                                @if ($post->image_url)
                                    <div class="post-img-container">
                                        <img class="post-img" src="{{ $post->image_url }}" alt="post_img">
                                    </div>
                                @endif
                            </div>
                            <div class="post-meta mt-2">
                                <small>🤍 {{ $post->likes->count() }} likes</small>
                                <small>{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>No hay posts todavía.</p>
                @endforelse
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection