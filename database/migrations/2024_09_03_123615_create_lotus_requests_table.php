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
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lotus_request_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_date_time'); // Begindatum en tijd
            $table->dateTime('end_date_time')->nullable(); // Einddatum en tijd
            $table->string('applicant'); // Aanvrager
            $table->string('location'); // Plaats
            $table->string('street_name'); // Straatnaam
            $table->string('house_number'); // Huisnummer
            $table->string('postal_code'); // Postcode
            $table->integer('number_of_people'); // Aantal personen
            $table->string('rate_group'); // Tariefgroep
            $table->text('special_requests')->nullable(); // Bijzonderheden
            $table->string('contact_person'); // Contactpersoon
            $table->string('contact_phone'); // Contactgegevens - telefoonnummer
            $table->string('contact_email')->nullable(); // Contactgegevens - email
            $table->string('payment_reference')->nullable(); // Betalingskenmerk
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
