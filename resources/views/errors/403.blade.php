@extends('layouts.app')

@section('title', '403 — Forbidden')

@section('content')
<div class="container py-5 text-center">
    <h1 class="game-title" style="font-size: 6rem; color: var(--purple);">403</h1>
    <h2 class="game-title mb-3">Access denied</h2>
    <p class="mb-4">You don't have permission to access this page.</p>
    <a href="/" class="btn-register">Back to Home</a>
</div>
@endsection