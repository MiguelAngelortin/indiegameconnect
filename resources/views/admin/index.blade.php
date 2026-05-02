@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
    <div class="container py-4">
        <div class="text-center mb-4">
            <h2 class="section-title d-inline-block px-4">ADMIN PANEL</h2>
        </div>
        <div class=" mb-3 justify-content-end d-flex">
            <a href="/admin/report" class="btn-pdf"><h4>Download Report PDF</h4></a>
        </div>
        <div class="admin-content">
        {{-- Quick links --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <a href="/admin/users" class="text-decoration-none">
                    <div class="dev-card text-center admin-link-card">
                        <h4 class="game-title" style="color: var(--font);">Users</h4>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="/admin/games" class="text-decoration-none">
                    <div class="dev-card text-center admin-link-card">

                        <h4 class="game-title" style="color: var(--font);">Games</h4>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="/admin/posts" class="text-decoration-none">
                    <div class="dev-card text-center admin-link-card">

                        <h4 class="game-title" style="color: var(--font);">Posts</h4>
                    </div>
                </a>
            </div>
        </div>

        <hr style="border-color: var(--purple); opacity: 1;">
        <h4 class="game-title text-center my-4" style="color: var(--purple);">Platform Statistics</h4>

        {{-- Stats --}}
        <div class="row g-3">
            <div class="col-6 col-md-4">
                <div class="dev-card text-center">
                    <h2 class="game-title" style="color: var(--purple);">{{ $totalUsers }}</h2>
                    <small>Total Users</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="dev-card text-center">
                    <h2 class="game-title" style="color: var(--purple);">{{ $totalGames }}</h2>
                    <small>Total Games</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="dev-card text-center">
                    <h2 class="game-title" style="color: var(--purple);">{{ $totalPosts }}</h2>
                    <small>Total Posts</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="dev-card text-center">
                    <h2 class="game-title" style="color: var(--purple);">{{ $newUsersThisMonth }}</h2>
                    <small>New Users this month</small>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="dev-card text-center">
                    <h2 class="game-title" style="color: var(--purple);">{{ $newGamesThisMonth }}</h2>
                    <small>New Games this month</small>
                </div>
            </div>
        </div>
    </div>
    </div>
    
@endsection
