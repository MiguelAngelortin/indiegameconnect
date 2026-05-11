@extends('layouts.app')

@section('title', 'Games Create')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-4 games-form">
                <h1 class="form-title">Game Creator</h1>

                {{-- El formulario usa multipart para permitir subida de archivos (cover_image) --}}
                <form action="/games/store" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Muestra todos los errores de validación del servidor --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Título del juego --}}
                    <div class="mb-3">
                        <label for="title">Title:</label>
                        {{-- old() recupera el valor si el formulario falla la validación --}}
                        <input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}"
                            required>
                    </div>

                    {{-- Descripción del juego --}}
                    <div class="mb-3">
                        <label for="description">Description:</label>
                        <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                    </div>

                    {{-- Selección de géneros mediante pills (checkboxes con estilo visual) --}}
                    <div class="mb-3">
                        <label>Genres:</label>
                        <div class="genres-container">
                            @foreach ($genres as $genre)
                                {{-- Si el formulario falla, old() mantiene los géneros seleccionados --}}
                                <label
                                    class="genre-pill {{ in_array($genre->id, old('genres', [])) ? 'genre-pill-active' : '' }}">
                                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                        {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }}>
                                    {{ $genre->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Estado de desarrollo del juego --}}
                    <div class="mb-3">
                        <label for="status">Status:</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="alpha" {{ old('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                            <option value="beta" {{ old('status') == 'beta' ? 'selected' : '' }}>Beta</option>
                            <option value="release" {{ old('status') == 'release' ? 'selected' : '' }}>Release</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    {{-- Motor gráfico usado para desarrollar el juego --}}
                    <div class="mb-3">
                        <label for="engine">Engine:</label>
                        <select class="form-control" name="engine" id="engine" required>
                            <option value="Unreal" {{ old('engine') == 'Unreal' ? 'selected' : '' }}>Unreal</option>
                            <option value="Unity" {{ old('engine') == 'Unity' ? 'selected' : '' }}>Unity</option>
                            <option value="Godot" {{ old('engine') == 'Godot' ? 'selected' : '' }}>Godot</option>
                            <option value="GameMaker" {{ old('engine') == 'GameMaker' ? 'selected' : '' }}>GameMaker
                            </option>
                            <option value="Other" {{ old('engine') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <h4>Optional fields:</h4>

                    {{-- Publisher o nombre del desarrollador (opcional) --}}
                    <div class="mb-3">
                        <label for="publisher">Publisher / Developer:</label>
                        <input class="form-control" type="text" name="publisher" id="publisher"
                            value="{{ old('publisher') }}">
                    </div>

                    {{-- Fecha de lanzamiento del juego (opcional) --}}
                    <div class="mb-3">
                        <label for="release_date">Release date:</label>
                        <input class="form-control" type="date" name="release_date" id="release_date"
                            value="{{ old('release_date') }}">
                    </div>

                    {{-- Imagen de portada del juego — se sube a Cloudinary (opcional) --}}
                    <div class="mb-3">
                        <label for="cover_image">Cover image:</label>
                        <input class="form-control" type="file" name="cover_image" id="cover_image" accept="image/*">
                        <small class="text-muted">Recommended size: 600x900px or 2/3 ratio</small>
                    </div>

                    {{-- Enlace de descarga del juego — puede ser itch.io, GameJolt u otro (opcional) --}}
                    <div class="mb-3">
                        <label for="download_url">Download URL:</label>
                        <input class="form-control" type="text" name="download_url" id="download_url"
                            value="{{ old('download_url') }}">
                        {{-- Accesos directos a plataformas de publicación de juegos indie --}}
                        <small class="text-muted">Don't have a link yet? Upload your game to one of these services:</small>
                        <div class="d-flex gap-2 mt-1">
                            <a href="https://itch.io/upload-new" target="_blank"
                                class="btn btn-sm btn-outline-light">itch.io</a>
                            <a href="https://gamejolt.com/dashboard" target="_blank"
                                class="btn btn-sm btn-outline-light">GameJolt</a>
                        </div>
                    </div>

                    {{-- Versión actual del juego (opcional) --}}
                    <div class="mb-3">
                        <label for="version">Version:</label>
                        <input class="form-control" type="text" name="version" id="version"
                            value="{{ old('version') }}">
                    </div>

                    <button type="submit">Create Game</button>
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