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
        Schema::create('residences_amenities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('residence_id')->nullable();
            $table->unsignedBigInteger('amenity_id')->nullable();

            $table->foreign('residence_id')->references('id')->on('residences')->onDelete('set null');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residences_amenities');
    }
};
