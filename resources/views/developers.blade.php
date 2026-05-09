@extends('layouts.app')
@section('title', __('developers.title'))
@section('content')
    <div class="container mt-4">

        {{-- Top 3 del mes --}}
        @if($topDevelopers->count() > 0)
        <div class="text-center">
            <h1 class="section-title mb-3">{{ __('developers.top_this_month') }}</h1>
            <br>
        </div>
        <div class="row g-3 mb-5 align-items-end justify-content-center">

            {{-- 2º puesto --}}
            @if(isset($topDevelopers[1]))
            <div class="col-12 col-md-3">
                <a href="/users/{{ $topDevelopers[1]->id }}" class="text-decoration-none">
                    <div class="dev-card text-center">
                        <i class="bi bi-trophy-fill medal medal-silver"></i>
                        <img src="{{ $topDevelopers[1]->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                            alt="avatar" class="profile-img my-2">
                        <h5>{{ $topDevelopers[1]->name }}</h5>
                        <small><i class="bi bi-people-fill me-1"></i>{{ $topDevelopers[1]->follows_count }} {{ __('developers.new_followers') }}</small>
                        @if($topDevelopers[1]->trustLevel)
                            <small class="d-block">{{ $topDevelopers[1]->trustLevel['label'] }} — {{ $topDevelopers[1]->trustLevel['percent'] }}%</small>
                            <div class="trust-bar-container mt-1">
                                <div class="trust-bar {{ $topDevelopers[1]->trustLevel['class'] }}" style="width: {{ $topDevelopers[1]->trustLevel['percent'] }}%"></div>
                            </div>
                        @endif
                    </div>
                </a>
            </div>
            @endif

            {{-- 1º puesto --}}
            @if(isset($topDevelopers[0]))
            <div class="col-12 col-md-4 order-md-gold">
                <a href="/users/{{ $topDevelopers[0]->id }}" class="text-decoration-none">
                    <div class="dev-card text-center dev-card-gold">
                        <i class="bi bi-trophy-fill medal medal-gold"></i>
                        <img src="{{ $topDevelopers[0]->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                            alt="avatar" class="profile-img-gold my-2">
                        <h5>{{ $topDevelopers[0]->name }}</h5>
                        <small><i class="bi bi-people-fill me-1"></i>{{ $topDevelopers[0]->follows_count }} {{ __('developers.new_followers') }}</small>
                        @if($topDevelopers[0]->trustLevel)
                            <small class="d-block">{{ $topDevelopers[0]->trustLevel['label'] }} — {{ $topDevelopers[0]->trustLevel['percent'] }}%</small>
                            <div class="trust-bar-container mt-1">
                                <div class="trust-bar {{ $topDevelopers[0]->trustLevel['class'] }}" style="width: {{ $topDevelopers[0]->trustLevel['percent'] }}%"></div>
                            </div>
                        @endif
                    </div>
                </a>
            </div>
            @endif

            {{-- 3º puesto --}}
            @if(isset($topDevelopers[2]))
            <div class="col-12 col-md-3">
                <a href="/users/{{ $topDevelopers[2]->id }}" class="text-decoration-none">
                    <div class="dev-card text-center">
                        <i class="bi bi-trophy-fill medal medal-bronze"></i>
                        <img src="{{ $topDevelopers[2]->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                            alt="avatar" class="profile-img my-2">
                        <h5>{{ $topDevelopers[2]->name }}</h5>
                        <small><i class="bi bi-people-fill me-1"></i>{{ $topDevelopers[2]->follows_count }} {{ __('developers.new_followers') }}</small>
                        @if($topDevelopers[2]->trustLevel)
                            <small class="d-block">{{ $topDevelopers[2]->trustLevel['label'] }} — {{ $topDevelopers[2]->trustLevel['percent'] }}%</small>
                            <div class="trust-bar-container mt-1">
                                <div class="trust-bar {{ $topDevelopers[2]->trustLevel['class'] }}" style="width: {{ $topDevelopers[2]->trustLevel['percent'] }}%"></div>
                            </div>
                        @endif
                    </div>
                </a>
            </div>
            @endif

        </div>
        @endif

        <hr>

        {{-- Buscador --}}
        <div class="mb-4">
            <form method="GET" action="/developers">
                <label for="search"><h2>{{ __('developers.search_label') }}</h2></label>
                <input type="text" name="search" class="form-control" placeholder="{{ __('developers.search_placeholder') }}" value="{{ request('search') }}">
            </form>
        </div>

        {{-- Grid developers --}}
        <div class="row g-3">
            @forelse ($developers as $developer)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="/users/{{ $developer->id }}" class="text-decoration-none">
                        <div class="dev-card text-center">
                            <img src="{{ $developer->profile_img ?? 'https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg' }}"
                                alt="avatar" class="profile-img mb-3">
                            <h5>{{ $developer->name }}</h5>
                            <small><i class="bi bi-people-fill me-1"></i>{{ $developer->follows_count }} {{ __('developers.followers') }}</small>
                            @if($developer->trustLevel)
                                <small class="d-block mt-1">{{ $developer->trustLevel['label'] }} — {{ $developer->trustLevel['percent'] }}%</small>
                                <div class="trust-bar-container mt-1">
                                    <div class="trust-bar {{ $developer->trustLevel['class'] }}" style="width: {{ $developer->trustLevel['percent'] }}%"></div>
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            @empty
                <p>{{ __('developers.no_developers') }}</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $developers->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection