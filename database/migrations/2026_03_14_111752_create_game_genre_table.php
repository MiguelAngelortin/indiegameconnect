<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla intermedia game_genre para la relación many-to-many
 * entre games y genres. Sigue la convención de nomenclatura de Laravel:
 * los dos modelos en singular y orden alfabético separados por guión bajo.
 */
return new class extends Migration
{
    /**
     * Crea la tabla intermedia game_genre.
     */
    public function up(): void
    {
        Schema::create('game_genre', function (Blueprint $table) {
            // Clave foránea al juego — si se borra el juego se eliminan sus géneros asociados
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            // Clave foránea al género — si se borra el género se elimina su asociación con juegos
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            // Sin id() propio — la clave primaria es la combinación game_id + genre_id
            // Sin timestamps() — es una tabla de relación pura sin datos propios
        });
    }

    /**
     * Elimina la tabla intermedia en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_genre');
    }
};