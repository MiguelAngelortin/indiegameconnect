@extends('layouts.app')
@section('title', $user->name)
@section('content')
<div class="container mt-4">
    {{-- Fila superior: perfil + donaciones --}}
    <div class="row g-3 mb-4">
        {{-- Card perfil --}}
        <div class="col-12 col-lg-6">
            <div class="dev-card text-center">
                <img src="{{ $user->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                     alt="avatar" class="profile-img mb-3">
                <h3 class="game-title">{{ $user->name }}</h3>
                <span class="genre-tag role-badge">{{ ucfirst($user->role) }}</span>
                <small>Member since {{ $user->created_at->format('M Y') }}</small>
                @if ($user->bio)
                    <p class="mt-3">{{ $user->bio }}</p>
                @endif
            </div>
        </div>
        {{-- Card donaciones (solo developer/admin) --}}
        @if ($user->role === 'developer' || $user->role === 'admin')
        <div class="col-12 col-lg-6">
            <div class="dev-card text-center">
                <h4 class="game-title mb-3">Support this developer</h4>
                <p>If you enjoy their games, consider supporting their work.</p>
                <p><small>Donations coming soon</small></p>
            </div>
        </div>
        @endif
    </div>
    {{-- Fila inferior: juegos --}}
    @if ($games)
    <div class="row">
        <div class="col-12">
            <h4 class="game-title mb-3">Games</h4>
            <div class="row g-3">
                @forelse ($games as $game)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="/games/{{ $game->id }}" class="text-decoration-none">
                            <div class="game-card">
                                <div class="game-card-img-container">
                                    <img src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                                </div>
                                <div class="game-card-body">
                                    <h6 class="game-title">{{ $game->title }}</h6>
                                    <div>
                                        @foreach ($game->genres as $genre)
                                            <span class="genre-tag ">{{ $genre->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <p>No games yet.</p>
                @endforelse
            </div>
            {{ $games->links() }}
        </div>
    </div>
    @endif
</div>
@endsection