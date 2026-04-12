@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            {{-- Actualizar info de perfil --}}
            <div class="games-form mb-4">
                <h2 class="form-title mb-4">Edit Profile</h2>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio:</label>
                        <textarea id="bio" name="bio" class="form-control" rows="3">{{ auth()->user()->bio }}</textarea>
                        @error('bio') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="profile_img" class="form-label">Profile image URL:</label>
                        <input type="text" id="profile_img" name="profile_img" class="form-control" value="{{ auth()->user()->profile_img }}">
                        @error('profile_img') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn-register">Save changes</button>
                </form>
            </div>
            {{-- Cambiar contraseña --}}
            <div class="games-form mb-4">
                <h2 class="form-title mb-4">Change Password</h2>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current password:</label>
                        <input type="password" id="current_password" name="current_password" class="form-control">
                        @error('current_password') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New password:</label>
                        <input type="password" id="password" name="password" class="form-control">
                        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm new password:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn-register">Update password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection