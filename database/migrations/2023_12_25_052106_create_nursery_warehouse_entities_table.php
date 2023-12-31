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
        Schema::create('nursery_warehouse_entities', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('price');
            $table->json('cash')->nullable();
            $table->json('installments')->nullable();

            $table->morphs('entity');
            $table->foreignId('entity_type_id')->constrained('entity_types');
            $table->foreignId('agricultural_supply_store_user_id')->constrained('agricultural_supply_store_users', 'id', 'agricultural_supply_store_user_id_foreign');
            $table->foreignId('nursery_user_id')->constrained('nursery_users');
            $table->foreignId('nursery_id')->constrained('nurseries');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nursery_warehouse_entities');
    }
};
