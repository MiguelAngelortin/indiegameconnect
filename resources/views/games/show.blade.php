@extends('layouts.app')

@section('title', 'Show Games')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-8">
                    {{-- info del juego --}}
                <div class="row">
                <div class="col-4">
                        {{-- Imagen --}}
                        <img class="game_img" src="{{ $game->cover_image }}" alt="game_img">
                        
                </div> 
                <div class="col-8">
                    {{-- Titulo del juego --}}
                        <span>{{ $game->title }}</span>
                    {{-- Generos --}}
                        @foreach ($game->genres as $genre)
                            <span>{{ $genre->name }}</span>
                        @endforeach
                    {{-- engine --}}
                <span>{{ $game->engine }}</span>
                {{-- status --}}
                <span>{{ $game->status }}</span>
                {{-- version --}}
                <span>{{ $game->version }}</span>
                @if ($game->download_url)
                    <button>Download</button>
                @endif
                </div>
                </div>
            </div>
            <div class="col-4">
                {{-- card del dev --}}
                <span>{{ $game->user->name }}</span>
                @if ($game->user->bio)
                <span>{{ $game->user->bio }}</span>
                @endif

                @if ($game->user->profile_img)
                <img src="{{ $game->user->profile_img }}" alt="profile_image">
                @endif
            </div>
        </div>
        {{-- devlog --}}
        devlog
        <div class="row mt-4">
            <div class="col-12">
                {{-- Posts --}}
                posts
            </div>
        </div>
    </div>
@endsection
