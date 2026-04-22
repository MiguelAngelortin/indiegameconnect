@extends('layouts.app')
@section('title', $user->name)
@section('content')
    <div class="container mt-4">
        {{-- Fila superior: perfil + trust + donaciones --}}
        <div class="row g-3 mb-4">
            {{-- Card perfil --}}
            <div class="col-12 col-lg-6">
                <div class="dev-card text-center h-100">
                    <img src="{{ $user->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                        alt="avatar" class="profile-img mb-3">
                    <h3 class="game-title">{{ $user->name }}</h3>
                    <span class="genre-tag role-badge">{{ ucfirst($user->role) }}</span>
                    <small>Member since {{ $user->created_at->format('M Y') }}</small>
                    @if ($followersCount > 0 || $ratingPercent !== null)
                        <div class="mt-2 d-flex gap-3 justify-content-center">
                            <small>👥 {{ $followersCount }} followers</small>
                        </div>
                    @endif
                    @if ($user->bio)
                        <p class="mt-3">{{ $user->bio }}</p>
                    @endif
                    @guest
                        @if ($user->role === 'developer' || $user->role === 'admin')
                            <div class="mt-3">
                                <button onclick="document.getElementById('loginModal').classList.add('active')"
                                    class="btn-register">
                                    Follow
                                </button>
                            </div>
                        @endif
                    @endguest
                    @auth
                        @if (Auth::user()->id === $user->id)
                            <a href="/profile" class="edit-profile-link">Edit Profile</a>
                        @endif
                        @if (Auth::user()->id !== $user->id && ($user->role === 'developer' || $user->role === 'admin'))
                            <div class="mt-3">
                                <form method="POST" action="/users/{{ $user->id }}/follow">
                                    @csrf
                                    <button type="submit" class="{{ $isFollowing ? 'btn-unfollow' : 'btn-register' }}">
                                        {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            {{-- Columna derecha: trust + donaciones --}}
            @if ($user->role === 'developer' || $user->role === 'admin')
                <div class="col-12 col-lg-6 d-flex flex-column gap-3">
                    {{-- Card trust level --}}
                    <div class="dev-card flex-fill text-center">
                        <h4 class="game-title mb-3">Trust Level</h4>
                        @if ($ratingPercent !== null)
                            @php
                                $trustClass =
                                    $ratingPercent >= 86
                                        ? 'trust-positive'
                                        : ($ratingPercent >= 61
                                            ? 'trust-positive'
                                            : ($ratingPercent >= 31
                                                ? 'trust-mixed'
                                                : 'trust-negative'));
                                $trustLabel =
                                    $ratingPercent >= 86
                                        ? 'Very Positive'
                                        : ($ratingPercent >= 61
                                            ? 'Positive'
                                            : ($ratingPercent >= 31
                                                ? 'Mixed'
                                                : 'Negative'));
                            @endphp
                            <p>{{ $trustLabel }} — {{ $ratingPercent }}%</p>
                            <div class="trust-bar-container">
                                <div class="trust-bar {{ $trustClass }}" style="width: {{ $ratingPercent }}%"></div>
                            </div>
                        @else
                            <p><small>No ratings yet</small></p>
                        @endif
                        @auth
                            @if (Auth::user()->id !== $user->id)
                                <div class="mt-3">
                                    <small class="d-block mb-2">Rate this developer</small>
                                    <div class="d-flex gap-4 justify-content-center">
                                        <form method="POST" action="/users/{{ $user->id }}/rate">
                                            @csrf
                                            <input type="hidden" name="rating" value="1">
                                            <button type="submit"
                                                class="rate-btn {{ $userRating && $userRating->rating == 1 ? 'voted' : '' }}">
                                                <img src="{{ asset('img/feliz-ico.png') }}" alt="Positive"
                                                    style="height:40px;">
                                            </button>
                                        </form>
                                        <form method="POST" action="/users/{{ $user->id }}/rate">
                                            @csrf
                                            <input type="hidden" name="rating" value="-1">
                                            <button type="submit"
                                                class="rate-btn {{ $userRating && $userRating->rating == -1 ? 'voted' : '' }}">
                                                <img src="{{ asset('img/triste-ico.png') }}" alt="Negative"
                                                    style="height:40px;">
                                            </button>
                                        </form>
                                    </div>
                                    @if ($userRating)
                                        <small class="d-block mt-2">
                                            {{ $userRating->rating == 1 ? 'You rated this developer positively' : 'You rated this developer negatively' }}
                                        </small>
                                    @endif
                                </div>
                            @endif
                        @endauth
                    </div>
                    {{-- Card donaciones --}}
                    <div class="dev-card flex-fill text-center">
                        <h4 class="game-title mb-3">Support this developer</h4>
                        <h5>Want to donate to this developer? <br><br>Ceck these links:</h5>
                        @if($user->donation_kofi || $user->donation_paypal || $user->donation_patreon || $user->donation_other)
                            <div class="d-flex flex-row flex-wrap gap-3 mt-3 justify-content-center">
                                @if ($user->donation_kofi)
                                    <a href="{{ $user->donation_kofi }}" target="_blank" class="btn-donation">
                                        <img src="{{ asset('img/kofi.png') }}" alt="Ko-fi">
                                    </a>
                                @endif
                                @if ($user->donation_paypal)
                                    <a href="{{ $user->donation_paypal }}" target="_blank" class="btn-donation">
                                        <img src="{{ asset('img/paypal.png') }}" alt="PayPal">
                                    </a>
                                @endif
                                @if ($user->donation_patreon)
                                    <a href="{{ $user->donation_patreon }}" target="_blank" class="btn-donation">
                                        <img src="{{ asset('img/patreon.png') }}" alt="Patreon">
                                    </a>
                                @endif
                                @if ($user->donation_other)
                                    <a href="{{ $user->donation_other }}" target="_blank" class="btn-other d-flex align-items-center gap-2">
                                        OTHER
                                    </a>
                                @endif
                            </div>
                        @else
                            <p><small>No donation links yet.</small></p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        {{-- Fila inferior: juegos --}}
        @if ($games)
            <div class="row">
                <div class="col-12">
                    <h4 class="game-title mb-3">Games</h4>
                    <div class="row g-3">
                        @forelse ($games as $game)
                            <div class="col-6 col-md-3 col-lg-2">
                                <a href="/games/{{ $game->id }}" class="text-decoration-none">
                                    <div class="game-card">
                                        <div class="game-card-img-container">
                                            <img src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                                        </div>
                                        <div class="game-card-body">
                                            <h6 class="game-title">{{ $game->title }}</h6>
                                            
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p>No games yet.</p>
                        @endforelse
                    </div>
                    {{ $games->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection