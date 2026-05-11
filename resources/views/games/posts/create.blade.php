@extends('layouts.app')
@section('title', 'New Post')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="games-form mt-4">
                {{-- Título del formulario con el nombre del juego al que pertenece el post --}}
                <h2 class="form-title mb-4">New Post — {{ $game->title }}</h2>

                {{-- El formulario usa multipart para permitir subida de imagen opcional --}}
                <form method="POST" action="/games/{{ $game->id }}/posts/store" enctype="multipart/form-data">
                    @csrf

                    {{-- Título del post --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                        {{-- @error muestra el error de validación solo del campo correspondiente --}}
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contenido del post --}}
                    <div class="mb-3">
                        <label for="content" class="form-label">Content:</label>
                        <textarea id="content" name="content" class="form-control" rows="6" required></textarea>
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Imagen del post — se sube a Cloudinary si se proporciona (opcional) --}}
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image (optional):</label>
                        <input type="file" id="image_url" name="image_url" class="form-control" accept="image/*">
                        @error('image_url')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-register">Publish Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection