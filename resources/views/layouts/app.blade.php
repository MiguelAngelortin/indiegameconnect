<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IndieGameConnect')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/4.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-md">
        <div class="container-fluid">
            <a href="/"><img class="navbar-logo" src="{{ asset('img/4.png') }}" alt="Logo"></a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/">{{ __('nav.home') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="/games">{{ __('nav.games') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="/developers">{{ __('nav.developers') }}</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/feed">{{ __('nav.feed') }}</a></li>
                        @if (Auth::user()->role === 'developer' || Auth::user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="/games/create">{{ __('nav.create_game') }}</a></li>
                        @endif
                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item d-flex align-items-center">
                                <span style="border-left: 1px solid var(--purple); height: 20px; margin: 0 0.5rem; opacity: 0.6;"></span>
                            </li>
                            <li class="nav-item"><a class="nav-link admin-nav-link" href="/admin">{{ __('nav.admin') }}</a></li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-2 lang-item">
                        <div class="lang-switcher">
                            <a href="{{ route('lang.switch', 'en') }}" class="nav-link py-0 {{ app()->getLocale() === 'en' ? 'active-lang' : '' }}">EN</a>
                            <span style="opacity: 0.4;">|</span>
                            <a href="{{ route('lang.switch', 'es') }}" class="nav-link py-0 {{ app()->getLocale() === 'es' ? 'active-lang' : '' }}">ES</a>
                        </div>
                    </li>
                    @guest
                        <li class="nav-item"><a class="nav-link" href="/login">{{ __('nav.login') }}</a></li>
                        <li class="nav-item"><a class="nav-link btn-register" href="/register">{{ __('nav.register') }}</a></li>
                    @endguest
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/users/{{ Auth::user()->id }}" style="border: 1px solid var(--purple); border-radius: 6px; padding: 4px 10px; font-weight: bold; color: var(--purple) !important;">{{ Auth::user()->name }}</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                               <button type="submit" class="btn-logout">{{ __('nav.logout') }}</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center">
            <p class="mb-0">IndieGameConnect &copy; {{ date('Y') }}</p>
            <div>
                <a class="footer-link me-3" href="/help">{{ __('nav.help') }}</a>
                <a class="footer-link me-3" href="/contact">{{ __('nav.contact') }}</a>
                <a class="footer-link" href="/legal">{{ __('nav.legal') }}</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    {{-- Modal login --}}
    <div id="loginModal" class="modal-overlay">
        <div class="games-form text-center" style="position:relative;">
            <button onclick="document.getElementById('loginModal').classList.remove('active')" class="modal-close">&times;</button>
            <h5 class="game-title mb-3">{{ __('modals.join_title') }}</h5>
            <p>{{ __('modals.join_text') }}</p>
            <a href="/register" class="btn-register me-2">{{ __('nav.register') }}</a>
            <a href="/login" class="btn-register">{{ __('nav.login') }}</a>
        </div>
    </div>

    {{-- Modal profile updated --}}
    @if (session('status') === 'profile-updated')
        <div id="successModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('successModal').classList.remove('active')" class="modal-close">&times;</button>
                <h5 class="game-title mb-3">{{ __('modals.profile_updated_title') }}</h5>
                <p>{{ __('modals.profile_updated_text') }}</p>
                <button onclick="document.getElementById('successModal').classList.remove('active')" class="btn-register">{{ __('modals.ok') }}</button>
            </div>
        </div>
    @endif

    {{-- Modal password updated --}}
    @if (session('status') === 'password-updated')
        <div id="passwordModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('passwordModal').classList.remove('active')" class="modal-close">&times;</button>
                <h5 class="game-title mb-3">{{ __('modals.password_updated_title') }}</h5>
                <p>{{ __('modals.password_updated_text') }}</p>
                <button onclick="document.getElementById('passwordModal').classList.remove('active')" class="btn-register">{{ __('modals.ok') }}</button>
            </div>
        </div>
    @endif

    {{-- Modal registro completado --}}
    @if (session('registered'))
        <div id="registeredModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('registeredModal').classList.remove('active')" class="modal-close">&times;</button>
                <h5 class="game-title mb-3">{{ __('modals.welcome_title') }}</h5>
                <h5 class="modal-app-title">IndieGameConnect</h5>
                <br>
                <p>{{ __('modals.welcome_text') }}</p>
                <button onclick="document.getElementById('registeredModal').classList.remove('active')" class="btn-register">{{ __('modals.welcome_btn') }}</button>
            </div>
        </div>
    @endif

    {{-- Modal throttle --}}
    @if (session('throttle_error'))
        <div id="throttleModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('throttleModal').classList.remove('active')" class="modal-close">&times;</button>
                <h5 class="game-title mb-3">{{ __('modals.throttle_title') }}</h5>
                <p>{{ session('throttle_error') }}</p>
                <button onclick="document.getElementById('throttleModal').classList.remove('active')" class="btn-register">{{ __('modals.ok') }}</button>
            </div>
        </div>
    @endif

    {{-- Modal primera visita --}}
    @if(isset($firstVisit) && $firstVisit)
        <div id="welcomeVisitorModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('welcomeVisitorModal').classList.remove('active')" class="modal-close">&times;</button>
                <h5 class="modal-app-title mb-1">{{ __('modals.visitor_title') }}</h5>
                <p>{{ __('modals.visitor_text') }}</p>
                <p>{{ __('modals.visitor_text2') }}</p>
                <a href="/help" class="btn-register">{{ __('modals.visitor_btn') }}</a>
            </div>
        </div>
    @endif

</body>

</html>