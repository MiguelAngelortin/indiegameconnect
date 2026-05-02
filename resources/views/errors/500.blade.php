@extends('layouts.app')

@section('title', '500 — Server Error')

@section('content')
<div class="container py-5 text-center">
    <h1 class="game-title" style="font-size: 6rem; color: var(--purple);">500</h1>
    <h2 class="game-title mb-3">Server error</h2>
    <p class="mb-4">Something went wrong on our end. Please try again later.</p>
    <a href="/" class="btn-register">Back to Home</a>
</div>
@endsection