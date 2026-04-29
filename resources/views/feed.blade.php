@extends('layouts.app')

@section('title', 'Mi Feed')

@section('content')
<div class="container py-4">
    <h2 class="section-title mb-4">Mi Feed</h2>

    @if($posts->isEmpty())
        <p class="text-muted">No sigues ningún juego todavía. ¡Explora y sigue algunos para ver su actividad aquí!</p>
    @else
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                @foreach($posts as $post)
                    <div class="d-flex gap-3 mb-4 align-items-start">
                        {{-- Card juego --}}
                        <a href="/games/{{ $post->game->id }}" class="text-decoration-none flex-shrink-0" style="width: 120px;">
                            <div class="game-card game-card-small">
                                <div class="game-card-img-container">
                                    <img src="{{ $post->game->cover_image ?? asset('images/default_cover.jpg') }}" alt="{{ $post->game->title }}">
                                </div>
                                <div class="game-card-body">
                                    <h6 class="game-title" style="white-space: normal; font-size: 1rem;">{{ $post->game->title }}</h6>
                                </div>
                            </div>
                        </a>
                        {{-- Post card --}}
                        <a href="/games/{{ $post->game->id }}/posts/{{ $post->id }}" class="text-decoration-none flex-fill">
                            <div class="card post-card">
                                <div class="card-body">
                                    <h5 class="game-title">{{ $post->title }}</h5>
                                    <p>{{ $post->content }}</p>
                                    @if($post->image_url)
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
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection