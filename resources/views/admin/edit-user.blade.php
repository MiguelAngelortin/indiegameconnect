@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="section-title d-inline-block px-4">EDIT USER</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="games-form">

                @if($errors->any())
                    <div class="alert-box alert-box-error mb-3">
                        @foreach($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/admin/users/{{ $user->id }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="developer" {{ $user->role === 'developer' ? 'selected' : '' }}>Developer</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="/admin/users" class="btn-register" style="background: var(--border); color: var(--font) !important;">Cancel</a>
                        <button type="submit" class="btn-register">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection