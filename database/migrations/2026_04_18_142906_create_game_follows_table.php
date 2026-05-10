<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla de seguimientos de juegos por parte de usuarios.
 * Permite a cualquier usuario seguir juegos para recibirlos en su feed
 * personalizado y que aparezcan en los rankings de la home.
 * Garantiza a nivel de BD que un usuario no puede seguir
 * el mismo juego dos veces mediante una restricción unique compuesta.
 */
return new class extends Migration
{
    /**
     * Crea la tabla game_follows con restricción de unicidad compuesta.
     */
    public function up(): void
    {
        Schema::create('game_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Usuario que sigue el juego — si se borra el usuario se borran sus follows
            $table->foreignId('game_id')->constrained()->cascadeOnDelete(); // Juego seguido — si se borra el juego se borran sus follows
            // Restricción unique compuesta — impide que el mismo usuario
            // pueda seguir el mismo juego dos veces a nivel de base de datos
            $table->unique(['user_id', 'game_id']);
            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla game_follows en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_follows');
    }
};