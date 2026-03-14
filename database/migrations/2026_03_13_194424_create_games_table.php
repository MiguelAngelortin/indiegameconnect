<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('publisher')->nullable();
            $table->date('release_date')->nullable();
            $table->enum('status', ['alpha','beta','release','cancelled']);
            $table->enum('engine', ['Unity','Unreal','Godot','GameMaker','Other']);
            $table->string('download_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('version');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
