<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

/**
 * Seeder principal de la aplicación.
 * Orquesta la ejecución de todos los seeders en el orden correcto,
 * respetando las dependencias entre tablas para evitar errores de
 * clave foránea. Se ejecuta con php artisan db:seed.
 */
class DatabaseSeeder extends Seeder
{
    // WithoutModelEvents desactiva los observers y eventos del modelo durante el seeding
    // Mejora el rendimiento al insertar grandes volúmenes de datos
    use WithoutModelEvents;

    /**
     * Llama a los seeders en el orden correcto.
     * GenreSeeder primero porque IndieGamesSeeder necesita los géneros
     * ya existentes para asociarlos a los juegos.
     * AdminSeeder se ejecuta dentro de IndieGamesSeeder junto al resto de usuarios.
     */
    public function run(): void
    {
        $this->call([
            GenreSeeder::class,      // Puebla la tabla genres con los 18 géneros disponibles
            IndieGamesSeeder::class, // Crea developers, admin, juegos y posts de prueba
        ]);
    }
}