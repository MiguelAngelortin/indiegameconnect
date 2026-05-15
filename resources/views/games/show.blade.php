@extends('layouts.app')
@section('title', 'Show Games')
@section('content')
    <div class="container mt-4">
        <div class="row g-3 align-items-stretch justify-content-center">

            {{-- Imagen de portada del juego --}}
            <div class="col-12 col-lg-game-img">
                {{-- Botones de edición y borrado visibles solo para el developer propietario del juego --}}
                @auth
                    @if (Auth::user()->id === $game->user_id)
                        <div class="d-flex gap-2 mb-1 justify-content-end">
                            <a href="/games/{{ $game->id }}/edit" class="game-edit-link">{{ __('games.edit') }}</a>
                            {{-- El formulario DELETE se envía desde el modal de confirmación, no directamente --}}
                            <form method="POST" action="/games/{{ $game->id }}" class="game-delete-form" id="form-delete-game">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="game-delete-link"
                                    onclick="openGameDeleteModal()">{{ __('games.delete') }}</button>
                            </form>
                        </div>
                    @endif
                @endauth
                {{-- Si no hay portada se muestra la imagen por defecto --}}
                <img class="game_img"
                    src="{{ $game->cover_image ? asset($game->cover_image) : asset('img/default_cover.jpg') }}"
                    alt="game_img">
            </div>

            {{-- Información principal del juego --}}
            <div class="col-12 col-lg-6">
                <h2 class="mt-3 game-title">{{ $game->title }}</h2>
                {{-- Géneros asociados al juego mediante la relación many-to-many --}}
                <div class="mb-2">
                    @foreach ($game->genres as $genre)
                        <span class="genre-tag">{{ $genre->name }}</span>
                    @endforeach
                </div>
                <p>{{ $game->description }}</p>
                <div class="d-flex gap-2 mt-3">
                    {{-- Card con detalles técnicos del juego --}}
                    <div class="game-details-card">
                        <small class="game-details">{{ __('games.game_details') }}</small><br>
                        <small>{{ __('games.engine') }} {{ $game->engine }}</small><br>
                        <small>{{ __('games.status') }} {{ $game->status }}</small><br>
                        <small>{{ __('games.version') }} {{ $game->version }}</small>
                    </div>
                    {{-- Botón de descarga deshabilitado visualmente si no hay URL --}}
                    @if ($game->download_url)
                        <a href="{{ $game->download_url }}" target="_blank"
                            class="btn-download d-none d-md-flex align-items-center justify-content-center flex-fill">{{ __('games.download') }}</a>
                    @else
                        <span class="btn-download d-none d-md-flex align-items-center justify-content-center flex-fill"
                            style="opacity: 0.4; cursor: not-allowed;">{{ __('games.not_available') }}</span>
                    @endif
                    {{-- Guests ven el botón Follow pero se les redirige al modal de login --}}
                    @guest
                        <button onclick="document.getElementById('loginModal').classList.add('active')"
                            class="btn-download d-flex align-items-center justify-content-center">
                            {{ __('games.follow_game') }}
                        </button>
                    @endguest
                    {{-- El propietario del juego no puede seguirse a sí mismo --}}
                    @auth
                        @if (Auth::user()->id !== $game->user_id)
                            <form method="POST" action="/games/{{ $game->id }}/follow" class="d-flex">
                                @csrf
                                {{-- Comprueba si el usuario ya sigue el juego para mostrar Follow o Unfollow --}}
                                @php
                                    $isFollowingGame = $game
                                        ->follows()
                                        ->where('user_id', Auth::user()->id)
                                        ->exists();
                                @endphp
                                <button type="submit"
                                    class="{{ $isFollowingGame ? 'btn-unfollow' : 'btn-download' }} d-flex align-items-center justify-content-center">
                                    {{ $isFollowingGame ? __('games.unfollow_game') : __('games.follow_game') }}
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Card del developer propietario del juego --}}
            <div class="col-12 col-lg-game-img d-flex mt-3 mt-lg-0">
                <div class="dev-card w-100 text-center">
                    <h5>{{ $game->user->name }}</h5>
                    @if ($game->user->profile_img)
                        <img src="{{ asset($game->user->profile_img) }}" alt="profile_image" class="profile-img my-3">
                    @endif
                    <div class="mt-2">
                        <small>{{ $game->user->follows()->count() }} {{ __('games.followers') }}</small>
                    </div>
                    <a href="/users/{{ $game->user->id }}"
                        class="btn-register mt-3 d-inline-block">{{ __('games.show_profile') }}</a>
                </div>
            </div>
        </div>

        {{-- Devlog — lista de posts del juego con paginación --}}
        <div class="row mt-4 justify-content-center">
            <div class="col-12 col-lg-8">
                {{-- Botón para crear nuevo post visible solo para el propietario del juego --}}
                @auth
                    @if (Auth::user()->id === $game->user_id)
                        <a href="/games/{{ $game->id }}/posts/create" class="btn-post mb-3">{{ __('games.new_post') }}</a>
                    @endif
                @endauth
                @forelse ($posts as $post)
                    {{-- Cada post es un enlace a su vista individual --}}
                    <a href="/games/{{ $game->id }}/posts/{{ $post->id }}" class="text-decoration-none">
                        <div class="card post-card mb-3">
                            <div class="card-body">
                                <h5 class="game-title">{{ $post->title }}</h5>
                                <p>{{ $post->content }}</p>
                                {{-- Imagen del post si existe --}}
                                @if ($post->image_url)
                                    <div class="post-img-container">
                                        <img class="post-img"
                                            src="{{ $post->image_url ? asset($post->image_url) : asset('img/default_cover.jpg') }}"
                                            alt="post_img">
                                    </div>
                                @endif
                            </div>
                            <div class="post-meta mt-2">
                                <small>🤍 {{ $post->likes->count() }} {{ __('games.likes') }}</small>
                                <small>{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>{{ __('games.no_posts') }}</p>
                @endforelse
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    {{-- Modal de confirmación de borrado del juego --}}
    <div id="gameDeleteModal" class="modal-overlay">
        <div class="games-form text-center" style="position:relative;">
            <button onclick="closeGameDeleteModal()" class="modal-close">&times;</button>
            <h5 class="game-title mb-3">{{ __('games.are_you_sure') }}</h5>
            <p>{{ __('games.delete_game_confirm') }}</p>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button onclick="closeGameDeleteModal()" class="btn-register"
                    style="background: var(--border); color: var(--font) !important;">{{ __('games.cancel') }}</button>
                {{-- Al confirmar se envía el formulario DELETE definido arriba --}}
                <button onclick="document.getElementById('form-delete-game').submit()" class="btn-register"
                    style="background: #c62828;">{{ __('games.delete') }}</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openGameDeleteModal() {
                document.getElementById('gameDeleteModal').classList.add('active');
            }

            function closeGameDeleteModal() {
                document.getElementById('gameDeleteModal').classList.remove('active');
            }
        </script>
    @endpush
@endsection
