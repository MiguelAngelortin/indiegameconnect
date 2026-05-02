@extends('layouts.app')

@section('title', '404 — Not Found')

@section('content')
<div class="container py-5 text-center">
    <h1 class="game-title" style="font-size: 6rem; color: var(--purple);">404</h1>
    <h2 class="game-title mb-3">Page not found</h2>
    <p class="mb-4">The page you're looking for doesn't exist or has been moved.</p>
    <a href="/" class="btn-register">Back to Home</a>
</div>
@endsection