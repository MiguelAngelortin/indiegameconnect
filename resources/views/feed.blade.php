{{-- Extiende el layout maestro que incluye navbar, footer y assets globales --}}
@extends('layouts.app')

@section('title', 'Mi Feed')

@section('content')
<div class="container py-4">

    <div class="text-center">
        <h1 class="section-title mb-4 d-inline-block">YOUR FEED</h1>
    </div>

    {{-- Si el usuario no sigue ningún juego se muestra un mensaje orientativo --}}
    @if($posts->isEmpty())
        <p class="text-muted">
            You're not following any games yet. Explore and follow some to see their activity here!
        </p>

    @else
        {{-- Columna centrada con ancho limitado para mejor legibilidad del feed --}}
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                @foreach($posts as $post)
                    {{-- Cada entrada del feed muestra la card del juego a la izquierda y el post a la derecha --}}
                    <div class="d-flex gap-3 mb-4 align-items-start">

                        {{-- Card miniatura del juego — flex-shrink-0 evita que se comprima con el post --}}
                        <a href="/games/{{ $post->game->id }}" class="text-decoration-none flex-shrink-0" style="width: 120px;">
                            <div class="game-card game-card-small">
                                <div class="game-card-img-container">
                                    {{-- Fallback a portada por defecto si el juego no tiene imagen --}}
                                    <img src="{{ $post->game->cover_image ?? asset('images/default_cover.jpg') }}"
                                         alt="{{ $post->game->title }}">
                                </div>
                                <div class="game-card-body">
                                    <h6 class="game-title" style="white-space: normal; font-size: 1rem;">
                                        {{ $post->game->title }}
                                    </h6>
                                </div>
                            </div>
                        </a>

                        {{-- Card del post — flex-fill ocupa el espacio restante junto a la miniatura --}}
                        <a href="/games/{{ $post->game->id }}/posts/{{ $post->id }}" class="text-decoration-none flex-fill">
                            <div class="card post-card">
                                <div class="card-body">
                                    <h5 class="game-title">{{ $post->title }}</h5>
                                    <p>{{ $post->content }}</p>
                                    {{-- La imagen del post es opcional — solo se renderiza si existe --}}
                                    @if($post->image_url)
                                        <div class="post-img-container">
                                            <img class="post-img" src="{{ $post->image_url }}" alt="post_img">
                                        </div>
                                    @endif
                                </div>
                                <div class="post-meta mt-2">
                                    {{-- $post->likes está cargado con with('likes') en FeedController --}}
                                    <small>🤍 {{ $post->likes->count() }} likes</small>
                                    {{-- diffForHumans() convierte el timestamp a formato legible: "2 hours ago" --}}
                                    <small>{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach

                {{-- Paginación estándar de Laravel — sin appends() porque el feed no tiene filtros --}}
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>

            </div>
        </div>
    @endif

</div>
@endsection