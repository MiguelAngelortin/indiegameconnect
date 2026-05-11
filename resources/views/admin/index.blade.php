@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="section-title d-inline-block px-4">ADMIN PANEL</h2>
    </div>

    <div class="admin-content">

        {{-- Accesos rápidos a las secciones de gestión --}}
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

        {{-- Estadísticas generales de la plataforma, calculadas en AdminController --}}
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

        {{-- Herramientas de administración — separadas visualmente del resto --}}
        <hr style="border-color: var(--purple); opacity: 1;" class="mt-4">
        <h4 class="game-title text-center my-4" style="color: var(--purple);">Admin Tools</h4>

        <div class="d-flex justify-content-center gap-2 align-items-center flex-wrap mb-4">
            <a href="/admin/report" class="btn-pdf" target="_blank"><h4>Download Report PDF</h4></a>
            <a href="/admin/export-database" class="btn-database"><h4>Export Database</h4></a>
            
            {{-- Importación agrupada visualmente --}}
<form action="{{ route('admin.import-database') }}" method="POST" enctype="multipart/form-data">
    @csrf
   <div class="d-flex gap-2 align-items-center px-3 py-2 mt-3" style="border: 1px solid var(--purple); border-radius: 8px;">
        <input type="file" name="sql_file" accept=".sql,.txt" class="form-control">
        <button type="submit" class="btn-database"><h4>Import Database</h4></button>
    </div>
</form>
        </div>

    </div>
</div>
@endsection