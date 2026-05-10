@extends('layouts.app')

@section('title', 'Manage Posts')

@section('content')
{{-- Listado de posts para el panel admin con búsqueda, ordenación y paginación --}}
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="section-title d-inline-block px-4">MANAGE POSTS</h2>
    </div>

    {{-- Buscador por título --}}
    <form method="GET" action="/admin/posts" class="mb-4 d-flex gap-2">
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
                    <th>Game</th>
                    <th>Author</th>
                    <th><a href="?sort=created_at&direction={{ $sortBy === 'created_at' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">Created {{ $sortBy === 'created_at' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td class="align-middle">{{ $post->id }}</td>
                        <td class="align-middle">
                            <a href="/games/{{ $post->game->id }}/posts/{{ $post->id }}" class="footer-link">{{ $post->title }}</a>
                        </td>
                        <td class="align-middle">
                            <a href="/games/{{ $post->game->id }}" class="footer-link">{{ $post->game->title }}</a>
                        </td>
                        <td class="align-middle">
                            <a href="/users/{{ $post->user->id }}" class="footer-link">{{ $post->user->name }}</a>
                        </td>
                        <td class="align-middle">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td class="align-middle">
                            <div class="d-flex gap-2">
                                <a href="/games/{{ $post->game->id }}/posts/{{ $post->id }}" class="game-edit-link">View</a>
                                <button type="button" class="game-delete-link" onclick="openDeletePostModal({{ $post->id }}, '{{ addslashes($post->title) }}')">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No posts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $posts->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Modal confirmación borrar post --}}
<div id="deletePostModal" class="modal-overlay">
    <div class="games-form text-center" style="position:relative;">
        <button onclick="document.getElementById('deletePostModal').classList.remove('active')" class="modal-close">&times;</button>
        <h5 class="game-title mb-3">Delete Post</h5>
        <p id="deletePostText"></p>
        <form id="deletePostForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="game-delete-link me-2">Delete</button>
            <button type="button" onclick="document.getElementById('deletePostModal').classList.remove('active')" class="btn-register">Cancel</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openDeletePostModal(postId, postTitle) {
        document.getElementById('deletePostText').textContent = 'Are you sure you want to delete "' + postTitle + '"?';
        document.getElementById('deletePostForm').action = '/admin/posts/' + postId;
        document.getElementById('deletePostModal').classList.add('active');
    }
</script>
@endpush