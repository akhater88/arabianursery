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
        Schema::create('nursery_seeds_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_user_id')->nullable()->constrained('farm_users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('seed_type_id')->nullable()->constrained('seed_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('agricultural_supply_store_user_id')->nullable()->constrained('agricultural_supply_store_users', 'id', 'agricultural_supply_store_nursery_seeds_sales_user_id_foreign')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('nursery_id')->constrained('nurseries')->cascadeOnDelete()->cascadeOnUpdate();;
            $table->foreignId('nursery_user_id')->constrained('nursery_users')->cascadeOnDelete()->cascadeOnUpdate();;
            $table->string('seed_class')->nullable();
            $table->integer('seed_count')->nullable();
            $table->decimal('price')->nullable();
            $table->json('cash')->nullable();
            $table->json('installments')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nursery_seeds_sales');
    }
};
