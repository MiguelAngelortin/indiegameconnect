@extends('layouts.app')
@section('title', 'Developers')
@section('content')
    <div class="container mt-4">
        <h2 class="game-title mb-4">Developers</h2>
        <div class="row g-3">
            @forelse ($developers as $developer)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="/users/{{ $developer->id }}" class="text-decoration-none">
                        <div class="dev-card text-center">
                            <img src="{{ $developer->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                                alt="avatar" class="profile-img mb-3">
                            <h5>{{ $developer->name }}</h5>
                            <small>👥 {{ $developer->follows_count }} followers</small>
                        </div>
                    </a>
                </div>
            @empty
                <p>No developers yet.</p>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $developers->links() }}
        </div>
    </div>
@endsection