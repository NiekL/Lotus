<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUserIdNullableOnLotusRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('lotus_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('lotus_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
    }
}

