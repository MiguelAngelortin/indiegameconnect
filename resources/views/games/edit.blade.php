@extends('layouts.app')

@section('title', 'Edit Game')

@section('content')

<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-md-8 col-lg-4 games-form">
    <h1 class="form-title">Edit Game</h1>

    {{-- PATCH simula el método HTTP correcto ya que los formularios HTML solo soportan GET y POST --}}
    <form action="/games/{{ $game->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Muestra todos los errores de validación del servidor --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Título del juego --}}
        <div class="mb-3">
            <label for="title">Title:</label>
            {{-- Se carga el valor actual del juego en lugar de old() porque el formulario ya tiene datos --}}
            <input class="form-control" type="text" name="title" id="title" value="{{ $game->title }}" required>
        </div>

        {{-- Descripción del juego --}}
        <div class="mb-3">
            <label for="description">Description:</label>
            <textarea class="form-control" name="description" id="description">{{ $game->description }}</textarea>
        </div>

        {{-- Selección de géneros — contains() comprueba si el juego ya tiene ese género asignado --}}
        <div class="mb-3">
            <label>Genres:</label>
            <div class="genres-container">
                @foreach($genres as $genre)
                    {{-- La pill aparece activa si el juego ya tiene ese género en la relación many-to-many --}}
                    <label class="genre-pill {{ $game->genres->contains($genre->id) ? 'genre-pill-active' : '' }}">
                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ $game->genres->contains($genre->id) ? 'checked' : '' }}>
                        {{ $genre->name }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Estado de desarrollo del juego --}}
        <div class="mb-3">
            <label for="status">Status:</label>
            <select class="form-control" name="status" id="status" required>
                <option value="alpha" {{ $game->status === 'alpha' ? 'selected' : '' }}>Alpha</option>
                <option value="beta" {{ $game->status === 'beta' ? 'selected' : '' }}>Beta</option>
                <option value="release" {{ $game->status === 'release' ? 'selected' : '' }}>Release</option>
                <option value="cancelled" {{ $game->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        {{-- Motor gráfico usado para desarrollar el juego --}}
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

        {{-- Publisher o nombre del desarrollador (opcional) --}}
        <div class="mb-3">
            <label for="publisher">Publisher / Developer:</label>
            <input class="form-control" type="text" name="publisher" id="publisher" value="{{ $game->publisher }}">
        </div>

        {{-- Fecha de lanzamiento del juego (opcional) --}}
        <div class="mb-3">
            <label for="release_date">Release date:</label>
            <input class="form-control" type="date" name="release_date" id="release_date" value="{{ $game->release_date }}">
        </div>

        {{-- Imagen de portada — si ya existe se muestra una previsualización antes del input --}}
        <div class="mb-3">
            <label for="cover_image">Cover image:</label>
            @if($game->cover_image)
                {{-- Previsualización de la portada actual almacenada en Cloudinary --}}
                <div class="mb-2">
                    <img src="{{ asset($game->cover_image) }}" style="width: 80px; border-radius: 4px;">
                    <small class="d-block text-muted">Current cover</small>
                </div>
            @endif
            {{-- Si no se sube nueva imagen se conserva la actual --}}
            <input class="form-control" type="file" name="cover_image" id="cover_image" accept="image/*">
            <small class="text-muted">Recommended size: 600x900px or 2/3. Leave empty to keep current.</small>
        </div>

        {{-- Enlace de descarga del juego — puede ser itch.io, GameJolt u otro (opcional) --}}
        <div class="mb-3">
            <label for="download_url">Download URL:</label>
            <input class="form-control" type="text" name="download_url" id="download_url" value="{{ $game->download_url }}">
            {{-- Accesos directos a plataformas de publicación de juegos indie --}}
            <small class="text-muted">Don't have a link yet? Upload your game to one of these services:</small>
            <div class="d-flex gap-2 mt-1">
                <a href="https://itch.io/upload-new" target="_blank" class="btn btn-sm btn-outline-light">itch.io</a>
                <a href="https://gamejolt.com/dashboard" target="_blank" class="btn btn-sm btn-outline-light">GameJolt</a>
            </div>
        </div>

        {{-- Versión actual del juego (opcional) --}}
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
        {{-- Al hacer clic en una pill se marca/desmarca el checkbox y se aplica el estilo activo --}}
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