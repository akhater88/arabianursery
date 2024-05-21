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
        Schema::table('nursery_seeds_sales', function (Blueprint $table) {
            $table->unsignedBigInteger('nursery_warehouse_entities_id')->after('farm_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nursery_seeds_sales', function (Blueprint $table) {
            $table->dropColumn('nursery_warehouse_entities_id');
        });
    }
};
