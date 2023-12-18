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
        Schema::create('seedling_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->string('seed_class');
            $table->integer('seed_count');
            $table->integer('tray_count');
            $table->unsignedTinyInteger('germination_rate');
            $table->unsignedInteger('germination_period');
            $table->decimal('price_per_tray');
            $table->decimal('additional_cost');
            $table->json('cash')->nullable();
            $table->json('installments')->nullable();
            $table->unsignedTinyInteger('status');

            $table->foreignId('seed_type_id')->constrained('seed_types');
            $table->foreignId('nursery_id')->constrained('nurseries');
            $table->foreignId('nursery_user_id')->constrained('nursery_users');
            $table->foreignId('farm_user_id')->constrained('farm_users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seedling_services');
    }
};
