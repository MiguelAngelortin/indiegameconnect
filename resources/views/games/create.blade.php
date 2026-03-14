@extends('layouts.app')

@section('title', 'Games Create')

@section('content')

<div class="container">
<div class="row justify-content-center">
<div class="col-4 games-form">
    <h1 class="form-title">Game Creator</h1>

    <form action="/games/store" method="POST">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
        <label for="title">Title:</label>
        <input class="form-control" type="text" name="title" id="title" required>
        </div>

        {{-- Desciption --}}
        <div class="mb-3">
        <label for="description">Description:</label>
        <textarea class="form-control" name="description" id="description"></textarea>
        </div>

        {{-- Status --}}
        <div class="mb-3">
        <label for="status">Status:</label>
        <select class="form-control" name="status" id="status" required>
        <option value="alpha">Alpha</option>
        <option value="beta">Beta</option>
        <option value="release">Release</option>
        <option value="cancelled">Cancelled</option>
        </select>
        </div>
        
        {{-- Engine --}}
        <div class="mb-3">
        <label for="engine">Engine:</label>
        <select class="form-control" name="engine" id="engine" required>
        <option value="unreal">Unreal</option>
        <option value="unity">Unity</option>
        <option value="godot">Godot</option>
        <option value="GameMaker">GameMaker</option>
        <option value="Other">Other</option>
        </select>
        </div>

        <h4>Not required but recommended fields:</h4>

        {{-- Publisher --}}
        <div class="mb-3">
        <label for="publisher">Publisher:</label>
        <input class="form-control" type="text" name="publisher" id="publisher">
        </div>

        {{-- Release date --}}
        <div class="mb-3">
        <label for="release_date">Release date:</label>
        <input class="form-control" type="date" name="release_date" id="release_date">
        </div>

        {{-- Cover image --}}
        <div class="mb-3">
        <label for="cover_image">Cover image:</label>
        <input class="form-control" type="text" name="cover_image" id="cover_image">
        </div>

        {{-- Download URL --}}
        <div class="mb-3">
        <label for="download_url">Download URL:</label>
        <input class="form-control" type="text" name="download_url" id="download_url">
        </div>

        {{-- Version --}}
        <div class="mb-3">
        <label for="version">Version:</label>
        <input class="form-control" type="text" name="version" id="version">
        </div>

        <button type="submit">Create Game</button>
    </form>
</div>
</div>
</div>
@endsection
