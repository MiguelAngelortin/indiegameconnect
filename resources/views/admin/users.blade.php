@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
{{-- Listado de usuarios para el panel admin con búsqueda, ordenación y paginación --}}
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="section-title d-inline-block px-4">MANAGE USERS</h2>
    </div>

    {{-- Buscador por nombre o email --}}
    <form method="GET" action="/admin/users" class="mb-4 d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
        <button type="submit" class="btn-register">Search</button>
    </form>

    <div style="overflow-x: auto;">
        <table class="table table-dark table-hover mb-0" style="border: 2px solid var(--purple); border-radius: 1rem; overflow: hidden;">
            @php $nextDir = $sortDir === 'asc' ? 'desc' : 'asc'; @endphp
            <thead>
                <tr>
                    <th><a href="?sort=id&direction={{ $sortBy === 'id' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">ID {{ $sortBy === 'id' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th><a href="?sort=name&direction={{ $sortBy === 'name' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">Name {{ $sortBy === 'name' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th><a href="?sort=email&direction={{ $sortBy === 'email' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">Email {{ $sortBy === 'email' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th><a href="?sort=role&direction={{ $sortBy === 'role' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">Role {{ $sortBy === 'role' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th><a href="?sort=created_at&direction={{ $sortBy === 'created_at' ? $nextDir : 'asc' }}&search={{ request('search') }}" class="footer-link">Joined {{ $sortBy === 'created_at' ? ($sortDir === 'asc' ? '▲' : '▼') : '' }}</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="align-middle">{{ $user->id }}</td>
                        <td class="align-middle"><a href="/users/{{ $user->id }}" class="footer-link">{{ $user->name }}</a></td>
                        <td class="align-middle">{{ $user->email }}</td>
                        <td class="align-middle"><span class="genre-tag">{{ ucfirst($user->role) }}</span></td>
                        <td class="align-middle">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="align-middle">
                            <div class="d-flex gap-2">
                                <a href="/admin/users/{{ $user->id }}/edit" class="game-edit-link">Edit</a>
                                @if($user->role !== 'admin')
                                    <form method="POST" action="/admin/users/{{ $user->id }}/ban" class="m-0">
                                        @csrf
                                        <button type="submit" class="game-edit-link" style="{{ $user->is_banned ? 'background: #4caf50;' : 'background: #ff9800;' }}">
                                            {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                        </button>
                                    </form>
                                    <button type="button" class="game-delete-link" onclick="openDeleteUserModal({{ $user->id }}, '{{ $user->name }}')">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Modal confirmación borrar usuario --}}
<div id="deleteUserModal" class="modal-overlay">
    <div class="games-form text-center" style="position:relative;">
        <button onclick="document.getElementById('deleteUserModal').classList.remove('active')" class="modal-close">&times;</button>
        <h5 class="game-title mb-3">Delete User</h5>
        <p id="deleteUserText"></p>
        <form id="deleteUserForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="game-delete-link me-2">Delete</button>
            <button type="button" onclick="document.getElementById('deleteUserModal').classList.remove('active')" class="btn-register">Cancel</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openDeleteUserModal(userId, userName) {
        document.getElementById('deleteUserText').textContent = 'Are you sure you want to delete ' + userName + '?';
        document.getElementById('deleteUserForm').action = '/admin/users/' + userId;
        document.getElementById('deleteUserModal').classList.add('active');
    }
</script>
@endpush