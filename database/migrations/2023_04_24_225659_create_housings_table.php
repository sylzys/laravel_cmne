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
        Schema::create('housings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('type');
            $table->string('floor');
            $table->string('orientation');
            $table->string('bedrooms');
            $table->string('bathrooms');
            $table->string('surface');
            $table->string('galery');
            $table->unsignedBigInteger('residence_id')->nullable();
            $table->foreign('residence_id')
                ->references('id')
                ->on('residences')
                ->onUpdate('cascade')
                ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housings');
    }
};
