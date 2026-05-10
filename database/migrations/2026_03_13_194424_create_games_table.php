<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla principal de juegos de la plataforma.
 * Cada juego pertenece a un usuario con rol developer o admin
 * y puede tener géneros, posts en el devlog y seguidores.
 */
return new class extends Migration
{
    /**
     * Crea la tabla games con todos sus campos.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();                                                        // Primary key autoincremental
            $table->string('title');                                             // Título del juego
            $table->string('description');                                       // Descripción del juego
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // Developer propietario — si se borra el usuario se borran sus juegos
            $table->string('publisher')->nullable();                             // Publisher opcional — null si es totalmente independiente
            $table->date('release_date')->nullable();                            // Fecha de lanzamiento opcional
            $table->enum('status', ['alpha', 'beta', 'release', 'cancelled']);  // Estado actual del desarrollo
            $table->enum('engine', ['Unity', 'Unreal', 'Godot', 'GameMaker', 'Other']); // Motor gráfico usado
            $table->string('download_url')->nullable();                          // Enlace de descarga opcional
            $table->string('cover_image')->nullable();                           // URL de portada en Cloudinary — null si no se ha subido
            $table->string('version')->nullable();                               // Versión actual del build — opcional
            $table->timestamps();                                                // created_at y updated_at automáticos
        });
    }

    /**
     * Elimina la tabla games en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};