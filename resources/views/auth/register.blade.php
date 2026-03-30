@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="games-form mt-4">
                <h2 class="form-title mb-4">Register</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="role" class="form-label">Role:</label>
                        <select name="role" id="role" class="form-select">
                            <option value="user">User</option>
                            <option value="developer">Developer</option>
                        </select>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3"> 
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm password:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn-register">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection