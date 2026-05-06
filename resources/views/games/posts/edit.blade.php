@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="games-form">
                <h2 class="form-title mb-4">Edit Post</h2>

                @if($errors->any())
                    <div class="alert-box alert-box-error mb-3">
                        @foreach($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/games/{{ $game->id }}/posts/{{ $post->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $post->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="6" required>{{ old('content', $post->content) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image <small class="text-muted">(opcional)</small></label>
                        @if($post->image_url)
                            <div class="mb-2">
                                <img src="{{ asset($post->image_url) }}" style="max-width: 200px; border-radius: 4px;">
                                <small class="d-block text-muted">Current image. Leave empty to keep it.</small>
                            </div>
                        @endif
                        <input type="file" name="image_url" class="form-control" accept="image/*">
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="/games/{{ $game->id }}/posts/{{ $post->id }}" class="btn-register" style="background: var(--border); color: var(--font) !important;">Cancel</a>
                        <button type="submit" class="btn-register">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection