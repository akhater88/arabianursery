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
            $table->string('seed_class')->nullable();
            $table->integer('seed_count')->nullable();
            $table->integer('tray_count');
            $table->unsignedTinyInteger('germination_rate')->nullable();
            $table->unsignedInteger('germination_period');
            $table->string('greenhouse_number')->nullable();
            $table->string('tunnel_greenhouse_number')->nullable();
            $table->decimal('price_per_tray');
            $table->decimal('additional_cost')->nullable();
            $table->json('cash')->nullable();
            $table->json('installments')->nullable();
            $table->string('status');

            $table->foreignId('seed_type_id')->constrained('seed_types');
            $table->foreignId('nursery_id')->constrained('nurseries');
            $table->foreignId('nursery_user_id')->constrained('nursery_users');
            $table->foreignId('farm_user_id')->nullable()->constrained('farm_users');

            $table->timestamps();
            $table->softDeletes();
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
