<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Migración base generada por Laravel con Breeze.
 * Crea las tres tablas fundamentales del sistema de autenticación:
 * users, password_reset_tokens y sessions.
 */
return new class extends Migration
{
    /**
     * Crea las tablas del sistema de autenticación.
     */
    public function up(): void
    {
        // Tabla principal de usuarios de la plataforma
        Schema::create('users', function (Blueprint $table) {
            $table->id();                                    // Primary key autoincremental
            $table->string('name');                          // Nombre del usuario
            $table->string('email')->unique();               // Email único — usado para el login
            $table->timestamp('email_verified_at')->nullable(); // Fecha de verificación del email
            $table->string('password');                      // Contraseña hasheada
            $table->rememberToken();                         // Token para "recordarme" en el login
            $table->timestamps();                            // created_at y updated_at automáticos
        });

        // Tabla para los tokens de restablecimiento de contraseña
        // El email es la clave primaria — solo puede haber un token activo por email
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabla para las sesiones de usuario gestionadas por Laravel
        // Permite persistir sesiones en BD en lugar de en ficheros del servidor
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();                  // ID único de sesión
            $table->foreignId('user_id')->nullable()->index(); // Usuario asociado (null si es guest)
            $table->string('ip_address', 45)->nullable();     // IP del cliente (soporta IPv6)
            $table->text('user_agent')->nullable();           // Navegador y sistema operativo
            $table->longText('payload');                      // Datos de la sesión serializados
            $table->integer('last_activity')->index();        // Timestamp de última actividad
        });
    }

    /**
     * Elimina las tablas creadas en up() en orden inverso.
     * Se ejecuta con php artisan migrate:rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};