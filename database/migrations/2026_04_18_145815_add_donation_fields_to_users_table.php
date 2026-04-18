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
    Schema::table('users', function (Blueprint $table) {
        $table->string('donation_kofi')->nullable();
        $table->string('donation_paypal')->nullable();
        $table->string('donation_patreon')->nullable();
        $table->string('donation_other')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['donation_kofi', 'donation_paypal', 'donation_patreon', 'donation_other']);
    });
}
};
