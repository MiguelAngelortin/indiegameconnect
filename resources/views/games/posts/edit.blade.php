@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="games-form">
                <h2 class="form-title mb-4">Edit Post</h2>

                {{-- Muestra todos los errores de validación del servidor --}}
                @if($errors->any())
                    <div class="alert-box alert-box-error mb-3">
                        @foreach($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- PATCH simula el método HTTP correcto ya que los formularios HTML solo soportan GET y POST --}}
                {{-- El formulario usa multipart para permitir subida de imagen opcional --}}
                <form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Título del post — old() recupera el valor si el formulario falla la validación, si no carga el valor actual --}}
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $post->title) }}" required>
                    </div>

                    {{-- Contenido del post --}}
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="6" required>{{ old('content', $post->content) }}</textarea>
                    </div>

                    {{-- Imagen del post — si ya existe se muestra una previsualización antes del input --}}
                    <div class="mb-3">
                        <label class="form-label">Image <small class="text-muted">(opcional)</small></label>
                        @if($post->image_url)
                            {{-- Previsualización de la imagen actual almacenada en Cloudinary --}}
                            <div class="mb-2">
                                <img src="{{ asset($post->image_url) }}" style="max-width: 200px; border-radius: 4px;">
                                {{-- Si no se sube nueva imagen se conserva la actual --}}
                                <small class="d-block text-muted">Current image. Leave empty to keep it.</small>
                            </div>
                        @endif
                        <input type="file" name="image_url" class="form-control" accept="image/*">
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        {{-- Cancel vuelve a la vista del post sin guardar cambios --}}
                        <a href="/games/{{ $game->id }}/posts/{{ $post->id }}" class="btn-register" style="background: var(--border); color: var(--font) !important;">Cancel</a>
                        <button type="submit" class="btn-register">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection