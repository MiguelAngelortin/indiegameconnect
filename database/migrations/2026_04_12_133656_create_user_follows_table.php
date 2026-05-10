<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Crea la tabla de seguimientos entre usuarios y developers.
 * Ambas claves foráneas apuntan a la tabla users porque tanto
 * el seguidor como el seguido son usuarios de la plataforma.
 * Garantiza a nivel de BD que un usuario no puede seguir
 * dos veces al mismo developer mediante una restricción unique compuesta.
 */
return new class extends Migration
{
    /**
     * Crea la tabla user_follows con restricción de unicidad compuesta.
     */
    public function up(): void
    {
        Schema::create('user_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();               // Usuario que realiza el seguimiento
            $table->foreignId('developer_id')->constrained('users')->cascadeOnDelete();   // Developer que es seguido — apunta explícitamente a 'users' porque el nombre no sigue la convención
            // Restricción unique compuesta — impide que el mismo usuario
            // pueda seguir dos veces al mismo developer a nivel de base de datos
            $table->unique(['user_id', 'developer_id']);
            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla user_follows en caso de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_follows');
    }
};