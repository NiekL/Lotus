<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('lotus_request_user', function (Blueprint $table) {
            $table->string('registration_number')->nullable();
        });
    }

    public function down()
    {
        Schema::table('lotus_request_user', function (Blueprint $table) {
            $table->dropColumn('registration_number');
        });
    }
};
