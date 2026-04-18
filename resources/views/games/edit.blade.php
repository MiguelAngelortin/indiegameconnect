@extends('layouts.app')

@section('title', 'Edit Game')

@section('content')

<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-4 games-form">
    <h1 class="form-title">Edit Game</h1>

    <form action="/games/{{ $game->id }}" method="POST">
        @csrf
        @method('PATCH')

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Title --}}
        <div class="mb-3">
            <label for="title">Title:</label>
            <input class="form-control" type="text" name="title" id="title" value="{{ $game->title }}" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description">Description:</label>
            <textarea class="form-control" name="description" id="description">{{ $game->description }}</textarea>
        </div>

        {{-- Genres --}}
        <div class="mb-3">
            <label>Genres:</label>
            <div class="genres-container">
                @foreach($genres as $genre)
                    <label class="genre-pill {{ $game->genres->contains($genre->id) ? 'genre-pill-active' : '' }}">
                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ $game->genres->contains($genre->id) ? 'checked' : '' }}>
                        {{ $genre->name }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status">Status:</label>
            <select class="form-control" name="status" id="status" required>
                <option value="alpha" {{ $game->status === 'alpha' ? 'selected' : '' }}>Alpha</option>
                <option value="beta" {{ $game->status === 'beta' ? 'selected' : '' }}>Beta</option>
                <option value="release" {{ $game->status === 'release' ? 'selected' : '' }}>Release</option>
                <option value="cancelled" {{ $game->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        {{-- Engine --}}
        <div class="mb-3">
            <label for="engine">Engine:</label>
            <select class="form-control" name="engine" id="engine" required>
                <option value="Unreal" {{ $game->engine === 'Unreal' ? 'selected' : '' }}>Unreal</option>
                <option value="Unity" {{ $game->engine === 'Unity' ? 'selected' : '' }}>Unity</option>
                <option value="Godot" {{ $game->engine === 'Godot' ? 'selected' : '' }}>Godot</option>
                <option value="GameMaker" {{ $game->engine === 'GameMaker' ? 'selected' : '' }}>GameMaker</option>
                <option value="Other" {{ $game->engine === 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <h4>Optional fields:</h4>

        {{-- Publisher --}}
        <div class="mb-3">
            <label for="publisher">Publisher:</label>
            <input class="form-control" type="text" name="publisher" id="publisher" value="{{ $game->publisher }}">
        </div>

        {{-- Release date --}}
        <div class="mb-3">
            <label for="release_date">Release date:</label>
            <input class="form-control" type="date" name="release_date" id="release_date" value="{{ $game->release_date }}">
        </div>

        {{-- Cover image --}}
        <div class="mb-3">
            <label for="cover_image">Cover image:</label>
            <input class="form-control" type="text" name="cover_image" id="cover_image" value="{{ $game->cover_image }}">
            <small class="text-muted">Recommended size: 600x900px or 2/3</small>
        </div>

        {{-- Download URL --}}
        <div class="mb-3">
            <label for="download_url">Download URL:</label>
            <input class="form-control" type="text" name="download_url" id="download_url" value="{{ $game->download_url }}">
        </div>

        {{-- Version --}}
        <div class="mb-3">
            <label for="version">Version:</label>
            <input class="form-control" type="text" name="version" id="version" value="{{ $game->version }}">
        </div>

        <button type="submit" class="btn-register">Save Changes</button>
    </form>
</div>
</div>
</div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.genre-pill').forEach(pill => {
            pill.addEventListener('click', function(e) {
                e.preventDefault();
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('genre-pill-active');
            });
        });
    </script>
@endpush