<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla de likes de los posts del devlog.
 * Garantiza a nivel de base de datos que un usuario solo puede
 * dar un like por post mediante una restricción unique compuesta.
 */
return new class extends Migration
{
    /**
     * Crea la tabla game_post_likes con restricción de unicidad compuesta.
     */
    public function up(): void
    {
        Schema::create('game_post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_post_id')->constrained()->cascadeOnDelete(); // Post que recibe el like — si se borra el post se borran sus likes
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();      // Usuario que dio el like — si se borra el usuario se borran sus likes
            // Restricción unique compuesta — impide que el mismo usuario
            // pueda dar más de un like al mismo post a nivel de base de datos
            $table->unique(['game_post_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla game_post_likes en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_post_likes');
    }
};