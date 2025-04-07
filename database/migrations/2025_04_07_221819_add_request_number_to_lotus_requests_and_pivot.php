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
        Schema::table('lotus_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('request_number')->nullable()->unique();
        });

        Schema::table('lotus_request_user', function (Blueprint $table) {
            $table->unsignedBigInteger('request_number')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lotus_requests_and_pivot', function (Blueprint $table) {
            //
        });
    }
};
