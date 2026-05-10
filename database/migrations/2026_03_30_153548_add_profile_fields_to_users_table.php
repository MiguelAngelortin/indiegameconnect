<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Añade los campos de perfil bio y profile_img a la tabla users.
 * Se creó como migración separada para mantener un historial
 * claro de la evolución de la tabla users a lo largo del proyecto.
 */
return new class extends Migration
{
    /**
     * Añade las columnas bio y profile_img a la tabla users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bio')->nullable();         // Biografía opcional del usuario — visible en su perfil público
            $table->string('profile_img')->nullable(); // URL de la foto de perfil en Cloudinary — null si no ha subido ninguna
        });
    }

    /**
     * Elimina las columnas bio y profile_img en caso de rollback.
     * dropColumn acepta un array para eliminar varias columnas en una sola llamada.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'profile_img']);
        });
    }
};