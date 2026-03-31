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
    Schema::table('game_post_comments', function (Blueprint $table) {
        $table->string('content');
        $table->foreignId('parent_id')->nullable()->constrained('game_post_comments')->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('game_post_comments', function (Blueprint $table) {
        $table->dropColumn(['content', 'parent_id']);
    });
}
};
