<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            ['name' => 'Platform'],
            ['name' => 'Puzzle'],
            ['name' => 'Racing'],
            ['name' => 'Roguelike'],
            ['name' => 'RPG'],
            ['name' => 'JRPG'],
            ['name' => 'Shooter'],
            ['name' => 'Simulation'],
            ['name' => 'Sports'],
            ['name' => 'Walking Simulator'],
        ]);
    }
}
