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
    Schema::create('game_follows', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('game_id')->constrained()->cascadeOnDelete();
        $table->unique(['user_id', 'game_id']);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('game_follows');
}
};
