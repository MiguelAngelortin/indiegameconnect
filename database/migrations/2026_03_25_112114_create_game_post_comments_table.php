<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla de comentarios y respuestas de los posts del devlog.
 * Implementa un sistema de comentarios anidados de dos niveles mediante
 * una clave foránea recursiva (parent_id) que apunta a la propia tabla.
 */
return new class extends Migration
{
    /**
     * Crea la tabla game_post_comments con soporte para comentarios anidados.
     */
    public function up(): void
    {
        Schema::create('game_post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_post_id')->constrained()->cascadeOnDelete(); // Post al que pertenece el comentario — si se borra el post se borran sus comentarios
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();      // Usuario que escribió el comentario — si se borra el usuario se borran sus comentarios
            $table->string('content');                                           // Contenido del comentario o respuesta
            // Clave foránea recursiva — apunta a la propia tabla game_post_comments
            // null indica comentario raíz; con valor indica reply a ese comentario
            // Si se borra el comentario padre se borran también todas sus replies
            $table->foreignId('parent_id')->nullable()->constrained('game_post_comments')->cascadeOnDelete();
            $table->timestamps();                                                // created_at y updated_at automáticos
        });
    }

    /**
     * Elimina la tabla game_post_comments en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_post_comments');
    }
};