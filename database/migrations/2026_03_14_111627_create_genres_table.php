<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla de géneros de videojuego.
 * Los géneros no los crean los usuarios sino que se precargan
 * mediante el GenreSeeder con los 18 géneros disponibles en la plataforma.
 */
return new class extends Migration
{
    /**
     * Crea la tabla genres con su estructura mínima.
     */
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();           // Primary key autoincremental
            $table->string('name'); // Nombre del género — ej: RPG, Platformer, Horror
            // Sin timestamps() — los géneros son datos estáticos que no necesitan fecha de creación
        });
    }

    /**
     * Elimina la tabla genres en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};