    @extends('layouts.app')
@section('title', 'Games')
@section('content')
<div class="container mt-4">
    {{-- Buscador y filtros --}}
    <form method="GET" action="/games" class="row g-2 mb-4">
        <div class="col-12 col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search games..." value="{{ request('search') }}">
        </div>
        <div class="col-6 col-md-3">
            <select name="genre" class="form-select">
                <option value="">All genres</option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">All status</option>
                <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                <option value="beta" {{ request('status') == 'beta' ? 'selected' : '' }}>Beta</option>
                <option value="release" {{ request('status') == 'release' ? 'selected' : '' }}>Release</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-12 col-md-2">
            <button type="submit" class="btn-register w-100">Search</button>
        </div>
    </form>
    {{-- Grid de juegos --}}
    <div class="row g-3">
        @forelse ($games as $game)
            {{-- card aquí --}}
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
                        <span class="genre-tag">{{ $genre->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </a>
</div>
        @empty
            <p>No games found.</p>
        @endforelse
    </div>
    {{-- Paginación --}}
    {{ $games->links() }}
</div>
@endsection
