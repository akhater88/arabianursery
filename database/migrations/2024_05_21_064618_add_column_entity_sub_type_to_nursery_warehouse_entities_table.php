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
        Schema::table('nursery_warehouse_entities', function (Blueprint $table) {
            $table->string('entity_sub_type')->nullable()->after('entity_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nursery_warehouse_entities', function (Blueprint $table) {
            $table->dropColumn('entity_sub_type');
        });
    }
};
