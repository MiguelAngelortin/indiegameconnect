@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="games-form mt-4">
                <h2 class="form-title mb-4">Login</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input type="checkbox" id="remember_me" name="remember">
                        <label for="remember_me">Remember me</label>
                    </div>
                    <button type="submit" class="btn-register">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection