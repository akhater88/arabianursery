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
        Schema::create('seedling_purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('tray_count');
            $table->decimal('price_per_tray');
            $table->json('cash')->nullable();
            $table->json('installments')->nullable();

            $table->unsignedBigInteger('seedling_service_id');
            $table->unsignedBigInteger('nursery_user_id');
            $table->unsignedBigInteger('nursery_id');
            $table->unsignedBigInteger('farm_user_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seedling_purchase_requests');
    }
};
