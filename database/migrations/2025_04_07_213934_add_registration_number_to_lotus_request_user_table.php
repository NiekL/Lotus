<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('request_number_tracker', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('highest_request_number')->default(1760000000);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('request_number_tracker', function (Blueprint $table) {
            $table->dropColumn('request_number_tracker');
        });
    }
};
