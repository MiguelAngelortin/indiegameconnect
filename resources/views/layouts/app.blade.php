<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IndieGameConnect')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/4.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Nunito:wght@400;600&display=swap"
        rel="stylesheet">
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
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/games">Games</a></li>
                    <li class="nav-item"><a class="nav-link" href="/developers">Developers</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/feed">My Feed</a></li>

                        @if (Auth::user()->role === 'developer' || Auth::user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="/games/create">Create Game</a></li>
                        @endif

                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item d-flex align-items-center">
                                <span
                                    style="border-left: 1px solid var(--purple); height: 20px; margin: 0 0.5rem; opacity: 0.6;"></span>
                            </li>
                            <li class="nav-item"><a class="nav-link admin-nav-link" href="/admin">Admin Panel</a></li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn-register" href="/register">Register</a></li>
                    @endguest
                    @auth
                        <li class="nav-item"><a class="nav-link"
                                href="/users/{{ Auth::user()->id }}">{{ Auth::user()->name }}</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link">Logout</button>
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

    {{-- Footer --}}
    <footer>
        <div
            class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 text-center">
            <div>
                <a class="footer-link me-3" href="/help">Help</a>
                <p class="mb-0 d-inline">IndieGameConnect &copy; {{ date('Y') }}</p>
            </div>
            <div>
                <a class="footer-link me-3" href="/contact">Contact</a>
                <a class="footer-link" href="/legal">Legal</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    {{-- Modal login --}}
    <div id="loginModal" class="modal-overlay">
        <div class="games-form text-center" style="position:relative;">
            <h5 class="game-title mb-3">Join the community!</h5>
            <p>Create an account to like, comment and follow developers.</p>
            <a href="/register" class="btn-register me-2">Register</a>
            <a href="/login" class="btn-register">Login</a>
            <br><br>
            <button onclick="document.getElementById('loginModal').classList.remove('active')"
                class="modal-close">&times;</button>
        </div>
    </div>

    {{-- Modal profile updated --}}
    @if (session('status') === 'profile-updated')
        <div id="successModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('successModal').classList.remove('active')"
                    class="modal-close">&times;</button>
                <h5 class="game-title mb-3">Profile updated!</h5>
                <p>Your changes have been saved successfully.</p>
                <button onclick="document.getElementById('successModal').classList.remove('active')"
                    class="btn-register">OK</button>
            </div>
        </div>
    @endif

    {{-- Modal password updated --}}
    @if (session('status') === 'password-updated')
        <div id="passwordModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('passwordModal').classList.remove('active')"
                    class="modal-close">&times;</button>
                <h5 class="game-title mb-3">Password updated!</h5>
                <p>Your password has been changed successfully.</p>
                <button onclick="document.getElementById('passwordModal').classList.remove('active')"
                    class="btn-register">OK</button>
            </div>
        </div>
    @endif

    {{-- Modal registro completado --}}
    @if (session('registered'))
        <div id="registeredModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('registeredModal').classList.remove('active')"
                    class="modal-close">&times;</button>
                <h5 class="game-title mb-3">Welcome to</h5>
                <h5 class="modal-app-title">IndieGameConnect</h5>
                <br>
                <p>Your account has been created successfully. Start discovering indie games!</p>
                <button onclick="document.getElementById('registeredModal').classList.remove('active')"
                    class="btn-register">Start the journey</button>
            </div>
        </div>
    @endif

    {{-- Modal throttle --}}
    @if (session('throttle_error'))
        <div id="throttleModal" class="modal-overlay active">
            <div class="games-form text-center" style="position:relative;">
                <button onclick="document.getElementById('throttleModal').classList.remove('active')"
                    class="modal-close">&times;</button>
                <h5 class="game-title mb-3">Slow down!</h5>
                <p>{{ session('throttle_error') }}</p>
                <button onclick="document.getElementById('throttleModal').classList.remove('active')"
                    class="btn-register">OK</button>
            </div>
        </div>
    @endif

{{-- Modal primera visita --}}
@if(isset($firstVisit) && $firstVisit)
    <div id="welcomeVisitorModal" class="modal-overlay active">
        <div class="games-form text-center" style="position:relative;">
            <button onclick="document.getElementById('welcomeVisitorModal').classList.remove('active')"
                class="modal-close">&times;</button>
            <h5 class="modal-app-title mb-1">IndieGameConnect</h5>
            <p>Discover, follow and support indie games and their developers.</p>
            <p>New here? Check the user manual to get started.</p>
            <a href="/help" class="btn-register">View Manual</a>
        </div>
    </div>
@endif

</body>

</html>
