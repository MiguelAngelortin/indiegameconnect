@extends('layouts.app')

@section('title', 'Manage Games')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="section-title d-inline-block px-4">MANAGE GAMES</h2>
    </div>

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
                                <form method="POST" action="/admin/games/{{ $game->id }}" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="game-delete-link" onclick="return confirm('Are you sure you want to delete this game?')">Delete</button>
                                </form>
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
        {{ $games->links() }}
    </div>
</div>
@endsection