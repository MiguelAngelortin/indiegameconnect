<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Añade los campos de donación a la tabla users.
 * Solo son relevantes para usuarios con rol developer o admin,
 * pero se añaden a la tabla users para simplificar la estructura
 * evitando una tabla separada para datos de developer.
 * Todos son nullable porque ningún developer está obligado a tener
 * plataformas de donación configuradas.
 */
return new class extends Migration
{
    /**
     * Añade las cuatro columnas de donación a la tabla users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('donation_kofi')->nullable();     // URL del perfil de Ko-fi del developer
            $table->string('donation_paypal')->nullable();   // URL o email de PayPal del developer
            $table->string('donation_patreon')->nullable();  // URL del perfil de Patreon del developer
            $table->string('donation_other')->nullable();    // Cualquier otra plataforma de donación
        });
    }

    /**
     * Elimina las cuatro columnas de donación en caso de rollback.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['donation_kofi', 'donation_paypal', 'donation_patreon', 'donation_other']);
        });
    }
};