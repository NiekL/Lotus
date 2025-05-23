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
        Schema::table('lotus_request_user', function (Blueprint $table) {
            $table->integer('user_played_time')->nullable();
            $table->integer('user_amount_km')->nullable();
            $table->text('user_feedback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lotus_request_user', function (Blueprint $table) {
            $table->dropColumn(['user_played_time', 'user_amount_km', 'user_feedback']);
        });
    }
};
