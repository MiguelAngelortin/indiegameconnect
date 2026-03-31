@extends('layouts.app')
@section('title', 'New Post')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="games-form mt-4">
                <h2 class="form-title mb-4">New Post — {{ $game->title }}</h2>
                <form method="POST" action="/games/{{ $game->id }}/posts/store">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content:</label>
                        <textarea id="content" name="content" class="form-control" rows="6" required></textarea>
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image URL (optional):</label>
                        <input type="text" id="image_url" name="image_url" class="form-control">
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