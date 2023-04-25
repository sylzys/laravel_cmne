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
            $table->string('bedrooms')->default(1);
            $table->string('bathrooms')->default(0);
            $table->integer('surface')->default(1);
            $table->text('galery')->nullable();
            $table->text('description')->nullable();
            $table->longblob('header')->nullable();
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
