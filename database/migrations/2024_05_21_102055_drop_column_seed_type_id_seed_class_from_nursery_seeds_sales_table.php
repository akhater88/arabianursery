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
            $table->dropForeign('nursery_seeds_sales_seed_type_id_foreign');
            $table->dropColumn(['seed_type_id','seed_class']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nursery_seeds_sales', function (Blueprint $table) {
            $table->foreignId('seed_type_id')->nullable()->after('farm_user_id')->constrained('seed_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('seed_class')->nullable()->after('seed_class');
        });
    }
};
