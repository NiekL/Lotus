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
        Schema::create('lotus_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('date');
            $table->time('arrival_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('amount_lotus');
            $table->string('payment_mark')->nullable();
            $table->integer('rate_group');
            $table->text('details')->nullable();
            $table->string('city');
            $table->string('street_name');
            $table->string('house_number');
            $table->string('zipcode');
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->string('contact_email');
            // Nieuwe velden
            $table->integer('filled_spots')->default(0); // Default value can be set as needed
            $table->integer('status')->default(1); // Assuming 1 might represent "active" status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotus_requests');
    }
};

