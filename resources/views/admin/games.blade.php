@extends('layouts.app')

@section('title', 'Manage Games')

@section('content')
{{-- Listado de juegos para el panel admin con búsqueda, ordenación y paginación --}}
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="section-title d-inline-block px-4">MANAGE GAMES</h2>
    </div>

    {{-- Buscador por título --}}
    <form method="GET" action="/admin/games" class="mb-4 d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Search by title..." value="{{ request('search') }}">
        <button type="submit" class="btn-register">Search</button>
    </form>

    <div style="overflow-x: auto;">
        <table class="table table-dark table-hover mb-0" style="border: 2px solid var(--purple); border-radius: 1rem; overflow: hidden;">
            @php $nextDir = $sortDir === 'asc' ? 'desc' : 'asc'; @endphp
            <thead>
                <tr>
                    <th><a href="?sort=id&direction={{ $sortBy === 'id' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">ID {{ $sortBy === 'id' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th><a href="?sort=title&direction={{ $sortBy === 'title' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">Title {{ $sortBy === 'title' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th>Developer</th>
                    <th><a href="?sort=status&direction={{ $sortBy === 'status' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">Status {{ $sortBy === 'status' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th><a href="?sort=created_at&direction={{ $sortBy === 'created_at' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">Created {{ $sortBy === 'created_at' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($games as $game)
                    <tr>
                        <td class="align-middle">{{ $game->id }}</td>
                        <td class="align-middle">
                            <a href="/games/{{ $game->id }}" class="footer-link">{{ $game->title }}</a>
                        </td>
                        <td class="align-middle">
                            <a href="/users/{{ $game->user->id }}" class="footer-link">{{ $game->user->name }}</a>
                        </td>
                        <td class="align-middle"><span class="genre-tag">{{ ucfirst($game->status) }}</span></td>
                        <td class="align-middle">{{ $game->created_at->format('d/m/Y') }}</td>
                        <td class="align-middle">
                            <div class="d-flex gap-2">
                                <a href="/games/{{ $game->id }}" class="game-edit-link">View</a>
                                <button type="button" class="game-delete-link" onclick="openDeleteGameModal({{ $game->id }}, '{{ addslashes($game->title) }}')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No games found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $games->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Modal confirmación borrar juego --}}
<div id="deleteGameModal" class="modal-overlay">
    <div class="games-form text-center" style="position:relative;">
        <button onclick="document.getElementById('deleteGameModal').classList.remove('active')" class="modal-close">&times;</button>
        <h5 class="game-title mb-3">Delete Game</h5>
        <p id="deleteGameText"></p>
        <form id="deleteGameForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="game-delete-link me-2">Delete</button>
            <button type="button" onclick="document.getElementById('deleteGameModal').classList.remove('active')" class="btn-register">Cancel</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openDeleteGameModal(gameId, gameTitle) {
        document.getElementById('deleteGameText').textContent = 'Are you sure you want to delete "' + gameTitle + '"?';
        document.getElementById('deleteGameForm').action = '/admin/games/' + gameId;
        document.getElementById('deleteGameModal').classList.add('active');
    }
</script>
@endpush