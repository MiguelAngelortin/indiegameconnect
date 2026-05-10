<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla de valoraciones de usuarios hacia developers.
 * Muy similar a user_follows pero añade el campo rating para almacenar
 * la opinión del usuario: 1 (positivo) o -1 (negativo).
 * La restricción unique compuesta garantiza una sola valoración por par usuario-developer.
 */
return new class extends Migration
{
    /**
     * Crea la tabla user_ratings con restricción de unicidad compuesta.
     */
    public function up(): void
    {
        Schema::create('user_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();             // Usuario que emite la valoración
            $table->foreignId('developer_id')->constrained('users')->cascadeOnDelete(); // Developer valorado — apunta explícitamente a 'users' porque el nombre no sigue la convención
            // tinyInteger ocupa 1 byte y es suficiente para almacenar solo dos valores: 1 y -1
            $table->tinyInteger('rating');
            // Restricción unique compuesta — un usuario solo puede valorar una vez a cada developer
            // Si quiere cambiar su valoración se actualiza el registro existente con updateOrCreate()
            $table->unique(['user_id', 'developer_id']);
            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla user_ratings en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ratings');
    }
};