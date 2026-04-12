<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IndieGameConnect')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    @stack('styles')
    
</head>
<body>
    <nav class="navbar navbar-expand-md">
        <div class="container-fluid">
            <a href="/"><img class="navbar-logo" src="{{ asset('img/4.png') }}" alt="Logo"></a>
            <button class="navbar-toggler"
                    data-bs-toggle="collapse"
                    data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/games">Games</a></li>
                    <li class="nav-item"><a class="nav-link" href="/developers">Developers</a></li>
                    @auth
    @if(Auth::user()->role === 'developer' || Auth::user()->role === 'admin')
        <li class="nav-item"><a class="nav-link" href="/games/create">Create Game</a></li>
    @endif
@endauth
                </ul>
                <ul class="navbar-nav ms-auto">
    @guest
        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
        <li class="nav-item"><a class="nav-link btn-register" href="/register">Register</a></li>
    @endguest
    @auth
        <li class="nav-item"><a class="nav-link" href="/users/{{ Auth::user()->id }}">{{ Auth::user()->name }}</a></li>
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
    <footer>
        <p>IndieGameConnect &copy; {{ date('Y') }}</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>