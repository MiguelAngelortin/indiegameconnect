<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Añade el campo role a la tabla users.
 * Se creó como migración separada para no modificar la migración base
 * generada por Laravel, manteniendo un historial de cambios claro.
 */
return new class extends Migration
{
    /**
     * Añade la columna role a la tabla users.
     * Usa Schema::table() en lugar de Schema::create() porque la tabla ya existe.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rol del usuario en la plataforma — por defecto 'user' al registrarse
            // Valores posibles: user, developer, admin
            $table->string('role')->default('user');
        });
    }

    /**
     * Elimina la columna role de la tabla users en caso de rollback.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};