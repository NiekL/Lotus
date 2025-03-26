<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lotus_requests', function (Blueprint $table) {
            $table->string('payment_mark_customer')->nullable()->after('payment_mark');
        });
    }

    public function down(): void
    {
        Schema::table('lotus_requests', function (Blueprint $table) {
            $table->dropColumn('payment_mark_customer');
        });
    }
};
