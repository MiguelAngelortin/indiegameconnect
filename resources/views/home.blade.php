@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Discover your next great indie</h1>
                    <p class="text-center">The platform where indie developers share their games and players discover them
                        before anyone else.</p>
                    <div class="text-center">
                        <a href="/games" class="btn-register">Let's discover games</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Functions --}}
    <section class="doubles">
        <div class="container-fluid">
            <div class="row align-items-center">
                {{-- Juego random --}}
                <div class="feature-card col-6">
                    <div>
                        <h2 class="section-title">Feel Lucky?</h2>
                    </div>
                    <div>
                        <p>Don't know what to play next? <br> Press the button and find a random game to support an indie developer.</p>
                        <a class="btn-register" href="/RandomGame">Random Game</a>
                    </div>
                </div>
                {{-- Joint the community --}}
                    <div class="feature-card col-6">
                        <div>
                            <h2 class="section-title">Join the community</h2>
                        </div>
                        <div>
                            <p>Support indie games and developers is easier if you are logged.</p>
                            <a class="btn-register" href="/register">Register</a>
                        </div>
                    
                </div>
            </div>
        </div>
    </section>

    {{-- Cards --}}
    <section class="cards">
        <div class="container">
            <div class="row">
                {{-- TOP GAMES --}}
                <div class="col-4">
                    <div>
                        <h2 class="section-title">Top Games</h2>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">Hollow Knight - Metroidvania</li>
                        <li class="list-group-item">Hades - Roguelike</li>
                    </ul>
                </div>
                {{-- Top Developers --}}
                <div class="col-4">
                    <div>
                        <h2 class="section-title">Top Developers</h2>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">Hollow Knight - Metroidvania</li>
                        <li class="list-group-item">Hades - Roguelike</li>
                    </ul>
                </div>

                {{-- In Deveopment --}}
                <div class="col-4">
                    <div>
                        <h2 class="section-title">In Development</h2>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">Hollow Knight - Metroidvania</li>
                        <li class="list-group-item">Hades - Roguelike</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
