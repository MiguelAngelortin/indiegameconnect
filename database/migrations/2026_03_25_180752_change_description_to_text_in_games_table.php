<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Cambia el tipo de la columna description en la tabla games
 * de string (VARCHAR 255) a text para soportar descripciones largas.
 * Se creó como migración separada al detectar que VARCHAR 255
 * era insuficiente para descripciones detalladas de juegos.
 */
return new class extends Migration
{
    /**
     * Cambia description de string a text en la tabla games.
     */
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // change() modifica una columna existente en lugar de crear una nueva
            $table->text('description')->change();
        });
    }

    /**
     * Revierte description de text a string en caso de rollback.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('description')->change();
        });
    }
};