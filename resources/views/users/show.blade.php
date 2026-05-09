@extends('layouts.app')
@section('title', $user->name)
@section('content')
    <div class="container mt-4">
        {{-- Fila superior: perfil + trust + donaciones --}}
        <div class="row g-3 mb-4">
            {{-- Card perfil --}}
            <div class="col-12 {{ $user->role === 'developer' || $user->role === 'admin' ? 'col-lg-6' : 'col-lg-4 mx-auto' }}">
                <div class="dev-card text-center h-100 {{ $user->role === 'user' ? 'profile-card-user' : '' }}">
                    <img src="{{ $user->profile_img ? asset($user->profile_img) : 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                        alt="avatar" class="profile-img mb-3">
                    <h3 class="game-title">{{ $user->name }}</h3>
                    <span class="genre-tag role-badge">{{ ucfirst($user->role) }}</span>
                    <small>{{ __('users.member_since') }} {{ $user->created_at->format('M Y') }}</small>
                    @if ($followersCount > 0 || $ratingPercent !== null)
                        <div class="mt-2 d-flex gap-3 justify-content-center">
                            <small>{{ $followersCount }} {{ __('users.followers') }}</small>
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
                                    {{ __('users.follow') }}
                                </button>
                            </div>
                        @endif
                    @endguest
                    @auth
                        @if (Auth::user()->id === $user->id)
                            <a href="/profile" class="edit-profile-link">{{ __('users.edit_profile') }}</a>
                        @endif
                        @if (Auth::user()->id !== $user->id && ($user->role === 'developer' || $user->role === 'admin'))
                            <div class="mt-3">
                                <form method="POST" action="/users/{{ $user->id }}/follow">
                                    @csrf
                                    <button type="submit" class="{{ $isFollowing ? 'btn-unfollow' : 'btn-register' }}">
                                        {{ $isFollowing ? __('users.unfollow') : __('users.follow') }}
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
                        <h4 class="game-title mb-3">{{ __('users.trust_level') }}</h4>
                        @if ($ratingPercent !== null)
                            @php
                                $trustClass = $ratingPercent >= 86 ? 'trust-positive' : ($ratingPercent >= 61 ? 'trust-positive' : ($ratingPercent >= 31 ? 'trust-mixed' : 'trust-negative'));
                                $trustLabel = $ratingPercent >= 86 ? __('users.very_positive') : ($ratingPercent >= 61 ? __('users.positive') : ($ratingPercent >= 31 ? __('users.mixed') : __('users.negative')));
                            @endphp
                            <p>{{ $trustLabel }} — {{ $ratingPercent }}%</p>
                            <div class="trust-bar-container">
                                <div class="trust-bar {{ $trustClass }}" style="width: {{ $ratingPercent }}%"></div>
                            </div>
                        @else
                            <p><small>{{ __('users.no_ratings') }}</small></p>
                        @endif
                        @auth
                            @if (Auth::user()->id !== $user->id)
                                <div class="mt-3">
                                    <small class="d-block mb-2">{{ __('users.rate_developer') }}</small>
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
                                            {{ $userRating->rating == 1 ? __('users.rated_positive') : __('users.rated_negative') }}
                                        </small>
                                    @endif
                                </div>
                            @endif
                        @endauth
                    </div>
                    {{-- Card donaciones --}}
                    <div class="dev-card flex-fill text-center">
                        <h4 class="game-title mb-3">{{ __('users.support_developer') }}</h4>
                        <h5 class="dev-card-support-text">{{ __('users.support_text') }} <br><br>{{ __('users.support_text2') }}</h5>
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
                            <p><small>{{ __('users.no_donations') }}</small></p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        {{-- Fila inferior: juegos --}}
        @if ($games)
            <div class="row">
                <div class="col-12">
                    <h4 class="game-title mb-3">{{ __('users.games_by') }} <span style="color: var(--purple);">{{ $user->name }}</span></h4>
                    <div class="row g-3">
                        @forelse ($games as $game)
                            <div class="col-6 col-md-3 col-lg-2">
                                <a href="/games/{{ $game->id }}" class="text-decoration-none">
                                    <div class="game-card">
                                        <div class="game-card-img-container">
                                            <img src="{{ $game->cover_image ? asset($game->cover_image) : asset('img/default_cover.jpg') }}" alt="{{ $game->title }}">
                                        </div>
                                        <div class="game-card-body">
                                            <h6 class="game-title">{{ $game->title }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p>{{ __('users.no_games') }}</p>
                        @endforelse
                    </div>
                    {{ $games->links() }}
                </div>
            </div>
        @endif

        {{-- Followed Games + Followed Developers --}}
        @if($followedGames->count() > 0 || $followedDevelopers->count() > 0)
            <div class="row mt-4 mb-4 g-3">
                {{-- Followed Games --}}
                <div class="col-12 col-lg-6">
                    <div class="dev-card h-100" style="border-color: var(--border); border-width: 3px;">
                        <h4 class="game-title mb-3"><span style="color: var(--purple);">{{ $user->name }}'s</span> {{ __('users.favourite_games') }}</h4>
                        @if($followedGames->count() > 0)
                            <div class="row g-2">
                                @foreach($followedGames as $game)
                                    <div class="col-6 col-md-4">
                                        <a href="/games/{{ $game->id }}" class="text-decoration-none">
                                            <div class="game-card game-card-small">
                                                <div class="game-card-img-container">
                                                    <img src="{{ $game->cover_image ? asset($game->cover_image) : asset('img/default_cover.jpg') }}" alt="{{ $game->title }}">
                                                </div>
                                                <div class="game-card-body">
                                                    <h6 class="game-title">{{ $game->title }}</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            @if($followedGamesCount > 6)
                                <div class="text-end mt-2">
                                    <a href="/games?followed_by={{ $user->id }}" class="footer-link">
                                        {{ __('users.view_all') }} ({{ $followedGamesCount }}) →
                                    </a>
                                </div>
                            @endif
                        @else
                            <p><small>{{ __('users.no_followed_games') }}</small></p>
                        @endif
                    </div>
                </div>
                {{-- Followed Developers --}}
                <div class="col-12 col-lg-6">
                    <div class="dev-card h-100" style="border-color: var(--border); border-width: 3px;">
                        <h4 class="game-title mb-3"><span style="color: var(--purple);">{{ $user->name }}'s</span> {{ __('users.favourite_developers') }}</h4>
                        @if($followedDevelopers->count() > 0)
                            <div class="row g-2">
                                @foreach($followedDevelopers as $follow)
                                    <div class="col-6 col-md-4">
                                        <a href="/users/{{ $follow->developer->id }}" class="text-decoration-none">
                                            <div class="dev-card text-center dev-card-small">
                                                <img src="{{ $follow->developer->profile_img ? asset($follow->developer->profile_img) : 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                                                    alt="avatar" class="profile-img mb-2">
                                                <h6 class="game-title">{{ $follow->developer->name }}</h6>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            @if($followedDevelopersCount > 6)
                                <div class="text-end mt-2">
                                    <a href="/developers?followed_by={{ $user->id }}" class="footer-link">
                                        {{ __('users.view_all') }} ({{ $followedDevelopersCount }}) →
                                    </a>
                                </div>
                            @endif
                        @else
                            <p><small>{{ __('users.no_followed_developers') }}</small></p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection