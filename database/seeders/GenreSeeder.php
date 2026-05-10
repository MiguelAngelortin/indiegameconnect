<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

/**
 * Seeder que puebla la tabla genres con los géneros disponibles en la plataforma.
 * Se ejecuta desde DatabaseSeeder al lanzar php artisan db:seed.
 */
class GenreSeeder extends Seeder
{
    /**
     * Inserta todos los géneros de una vez con insert() en lugar de create()
     * para evitar una query por cada registro.
     */
    public function run(): void
    {
        Genre::insert([
            ['name' => 'Action'],
            ['name' => 'Adventure'],
            ['name' => 'Beat-em-up'],
            ['name' => 'Hack-n-Slash'],
            ['name' => 'Horror'],
            ['name' => 'MOBA'],
            ['name' => 'Metroidvania'],
            ['name' => 'Open World'],
            ['name' => 'Platformer'],
            ['name' => 'Puzzle'],
            ['name' => 'Racing'],
            ['name' => 'Roguelike'],
            ['name' => 'RPG'],
            ['name' => 'JRPG'],
            ['name' => 'Shooter'],
            ['name' => 'Simulation'],
            ['name' => 'Sports'],
            ['name' => 'Narrative'],
            ['name' => 'Strategy'],
        ]);
    }
}