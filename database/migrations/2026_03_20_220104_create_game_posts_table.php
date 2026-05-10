<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla de posts del devlog de cada juego.
 * Los posts los publican los developers para informar a los seguidores
 * del progreso del desarrollo. Pueden recibir likes y comentarios.
 */
return new class extends Migration
{
    /**
     * Crea la tabla game_posts con todos sus campos.
     */
    public function up(): void
    {
        Schema::create('game_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete(); // Juego al que pertenece el post — si se borra el juego se borran sus posts
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Developer que publicó el post — si se borra el usuario se borran sus posts
            $table->string('title');                                        // Título del post
            $table->text('content');                                        // Contenido del post — text para soportar textos largos
            $table->string('image_url')->nullable();                        // URL de imagen en Cloudinary — opcional
            $table->timestamps();                                           // created_at y updated_at automáticos
        });
    }

    /**
     * Elimina la tabla game_posts en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_posts');
    }
};