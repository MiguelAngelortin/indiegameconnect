@extends('layouts.app')
@section('title', 'Show Games')
@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12 col-lg-8">
                {{-- game info --}}
                <div class="row">
                    <div class="col-12 col-md-4">
                        {{-- Game Image --}}
                        <img class="game_img" src="{{ $game->cover_image }}" alt="game_img">
                    </div>
                    <div class="col-12 col-md-8">
                        {{-- Title --}}
                        <h2 class="mt-3 game-title">{{ $game->title }}</h2>
                        {{-- Genres --}}
                        <div class="mb-2">
                            @foreach ($game->genres as $genre)
                                <span class="genre-tag">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        {{-- Description --}}
                        <p>{{ $game->description }}</p>
                        {{-- Technical data --}}
                        <p>
                            <small>Engine: {{ $game->engine }}</small><br>
                            <small>Status: {{ $game->status }}</small><br>
                            <small>Version: {{ $game->version }}</small>
                        </p>
                        {{-- Download --}}
                        @if ($game->download_url)
                            <a href="{{ $game->download_url }}" target="_blank" class="btn-register">Download</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                {{-- Dev card --}}
                <h5>{{ $game->user->name }}</h5>
                @if ($game->user->bio)
                    <p>{{ $game->user->bio }}</p>
                @endif
                @if ($game->user->profile_img)
                    <img src="{{ $game->user->profile_img }}" alt="profile_image">
                @endif
            </div>
        </div>
        {{-- Devlog --}}
<div class="row mt-4">
    <div class="col-12">
        @auth
    @if(Auth::user()->id === $game->user_id)
        <a href="/games/{{ $game->id }}/posts/create" class="btn-register mb-3">New Post</a>
    @endif
@endauth
        @forelse ($posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $post->title }}</h5>
                    <p>{{ $post->content }}</p>
                    @if ($post->image_url)
                        <img class="post-img" src="{{ $post->image_url }}" alt="post_img">
                    @endif
                    <small>{{ $post->created_at->diffForHumans() }}</small>
                    <small>{{ $post->likes->count() }} likes</small>
                </div>
            </div>
        @empty
            <p>No hay posts todavía.</p>
        @endforelse
        {{ $posts->links() }}
    </div>
</div>
    </div>
@endsection