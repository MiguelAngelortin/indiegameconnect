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
                        <a href="/games" class="btn-register">Games</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Cards --}}
    <section>
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
