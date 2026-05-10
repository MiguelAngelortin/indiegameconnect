<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Añade el campo is_banned a la tabla users para el sistema de moderación.
 * Permite al administrador banear usuarios sin eliminarlos de la plataforma,
 * conservando su historial y pudiendo reactivar la cuenta si es necesario.
 */
return new class extends Migration
{
    /**
     * Añade la columna is_banned a la tabla users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // boolean genera una columna TINYINT(1) en MySQL
            // default(false) garantiza que todos los usuarios nuevos empiezan sin baneo
            $table->boolean('is_banned')->default(false);
        });
    }

    /**
     * Elimina la columna is_banned en caso de rollback.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_banned');
        });
    }
};