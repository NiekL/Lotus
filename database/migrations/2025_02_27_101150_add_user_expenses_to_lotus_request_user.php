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
            //
            $table->float('user_expenses', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lotus_request_user', function (Blueprint $table) {
            //
            Schema::table('lotus_request_user', function (Blueprint $table) {
                $table->dropColumn('user_expenses'); // Remove only the new column
            });
        });
    }
};
