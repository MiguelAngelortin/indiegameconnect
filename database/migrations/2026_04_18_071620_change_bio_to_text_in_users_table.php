<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Cambia el tipo de la columna bio en la tabla users
 * de string (VARCHAR 255) a text para soportar biografías largas.
 * Mismo motivo que el cambio de description en games:
 * VARCHAR 255 era insuficiente para textos de varios párrafos.
 */
return new class extends Migration
{
    /**
     * Cambia bio de string a text en la tabla users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // change() modifica la columna existente sin perder los datos almacenados
            $table->text('bio')->nullable()->change();
        });
    }

    /**
     * Revierte bio de text a string en caso de rollback.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bio')->nullable()->change();
        });
    }
};