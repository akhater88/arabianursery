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
        Schema::create('seedling_service_images', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');

            $table->foreignId('seedling_service_id')->nullable()->constrained('seedling_services');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seedling_service_images');
    }
};
