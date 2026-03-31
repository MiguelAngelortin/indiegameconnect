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
                <small>{{ $post->likes->count() }} likes</small>
            </div>
            {{-- Comments --}}
            <div class="mt-4">
                <h5 class="game-title">Comments</h5>
                {{-- aquí irán los comentarios --}}
            </div>
        </div>
    </div>
</div>
@endsection