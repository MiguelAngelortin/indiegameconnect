@extends('layouts.app')
@section('title', $post->title)
@section('content')
<div class="container mt-4">
    {{-- Post --}}
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="games-form">
                <h2 class="game-title">{{ $post->title }}</h2>
                <small>{{ $post->created_at->diffForHumans() }} — {{ $post->user->name }}</small>
                <p class="mt-3">{{ $post->content }}</p>
                @if ($post->image_url)
                    <img class="post-img mb-3" src="{{ $post->image_url }}" alt="post_img">
                @endif
                @auth
<form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}/like">
    @csrf
    @if ($post->likes->where('user_id', auth()->user()->id)->count())
        <button type="submit" class="btn-register">🖤 {{ $post->likes->count() }} Likes</button>
    @else
        <button type="submit" class="btn-register">🤍 {{ $post->likes->count() }} Likes</button>
    @endif
</form>
@endauth
@guest
    <small>{{ $post->likes->count() }} likes</small>
@endguest
            </div>
            {{-- Comments --}}
            <div class="mt-4">
                <h5 class="game-title">Comments</h5>
                {{-- Formulario comentar --}}
@auth
<form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}/comments/store" class="mb-4">
    @csrf
    <div class="mb-3">
        <textarea name="content" class="form-control" rows="3" placeholder="Write a comment..." required></textarea>
        @error('content')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn-register">Comment</button>
</form>
@endauth

{{-- Lista de comentarios --}}
@forelse ($comments as $comment)
    <div class="card mb-3">
        <div class="card-body">
            <strong>{{ $comment->user->name }}</strong>
            <small class="ms-2">{{ $comment->created_at->diffForHumans() }}</small>
            <p class="mt-2">{{ $comment->content }}</p>
            {{-- Respuestas --}}
            @foreach ($comment->replies as $reply)
                <div class="card mt-2">
                    <div class="card-body">
                        <strong>{{ $reply->user->name }}</strong>
                        <small class="ms-2">{{ $reply->created_at->diffForHumans() }}</small>
                        <p class="mt-2">{{ $reply->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <p>No comments yet.</p>
@endforelse
            </div>
        </div>
    </div>
</div>
@endsection