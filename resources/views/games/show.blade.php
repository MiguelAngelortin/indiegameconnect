@extends('layouts.app')
@section('title', 'Show Games')
@section('content')
    <div class="container mt-4">
        <div class="row g-3 align-items-stretch justify-content-center">
            {{-- Imagen del juego --}}
            <div class="col-12 col-lg-game-img">
                @auth
                    @if (Auth::user()->id === $game->user_id)
                        <div class="d-flex gap-2 mb-1 justify-content-end">
                            <a href="/games/{{ $game->id }}/edit" class="game-edit-link">{{ __('games.edit') }}</a>
                            <form method="POST" action="/games/{{ $game->id }}" class="game-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="game-delete-link" onclick="return confirm('{{ __('games.delete_confirm') }}')">{{ __('games.delete') }}</button>
                            </form>
                        </div>
                    @endif
                @endauth
                <img class="game_img" src="{{ $game->cover_image ? asset($game->cover_image) : asset('img/default_cover.jpg') }}" alt="game_img">
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
                <div class="d-flex gap-2 mt-3">
                    <div class="game-details-card">
                        <small class="game-details">{{ __('games.game_details') }}</small><br>
                        <small>{{ __('games.engine') }} {{ $game->engine }}</small><br>
                        <small>{{ __('games.status') }} {{ $game->status }}</small><br>
                        <small>{{ __('games.version') }} {{ $game->version }}</small>
                    </div>
                    @if ($game->download_url)
                        <a href="{{ $game->download_url }}" target="_blank" class="btn-download d-none d-md-flex align-items-center justify-content-center flex-fill">{{ __('games.download') }}</a>
                    @else
                        <span class="btn-download d-none d-md-flex align-items-center justify-content-center flex-fill" style="opacity: 0.4; cursor: not-allowed;">{{ __('games.not_available') }}</span>
                    @endif
                    @guest
                        <button onclick="document.getElementById('loginModal').classList.add('active')" class="btn-download d-flex align-items-center justify-content-center">
                            {{ __('games.follow_game') }}
                        </button>
                    @endguest
                    @auth
                        @if (Auth::user()->id !== $game->user_id)
                            <form method="POST" action="/games/{{ $game->id }}/follow" class="d-flex">
                                @csrf
                                @php
                                    $isFollowingGame = $game->follows()->where('user_id', Auth::user()->id)->exists();
                                @endphp
                                <button type="submit" class="btn-download d-flex align-items-center justify-content-center {{ $isFollowingGame ? 'btn-unfollow' : '' }}">
                                    {{ $isFollowingGame ? __('games.unfollow_game') : __('games.follow_game') }}
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
            {{-- Card developer --}}
            <div class="col-12 col-lg-game-img d-flex mt-3 mt-lg-0">
                <div class="dev-card w-100 text-center">
                    <h5>{{ $game->user->name }}</h5>
                    @if ($game->user->profile_img)
                        <img src="{{ asset($game->user->profile_img) }}" alt="profile_image" class="profile-img my-3">
                    @endif
                    <div class="mt-2">
                        <small>{{ $game->user->follows()->count() }} {{ __('games.followers') }}</small>
                    </div>
                    <a href="/users/{{ $game->user->id }}" class="btn-register mt-3 d-inline-block">{{ __('games.show_profile') }}</a>
                </div>
            </div>
        </div>
        {{-- Devlog --}}
        <div class="row mt-4 justify-content-center">
            <div class="col-12 col-lg-8">
                @auth
                    @if (Auth::user()->id === $game->user_id)
                        <a href="/games/{{ $game->id }}/posts/create" class="btn-post mb-3">{{ __('games.new_post') }}</a>
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
                                        <img class="post-img" src="{{ $post->image_url ? asset($post->image_url) : asset('img/default_cover.jpg') }}" alt="post_img">
                                    </div>
                                @endif
                            </div>
                            <div class="post-meta mt-2">
                                <small>🤍 {{ $post->likes->count() }} {{ __('games.likes') }}</small>
                                <small>{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>{{ __('games.no_posts') }}</p>
                @endforelse
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection