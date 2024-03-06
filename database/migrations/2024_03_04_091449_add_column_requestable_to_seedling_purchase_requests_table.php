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
        Schema::table('seedling_purchase_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('requestedby')->after('farm_user_id');
            $table->string('requestedby_type')->after('requestedby');
            $table->integer('status')->after('requestedby_type')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seedling_purchase_requests', function (Blueprint $table) {
            $table->dropColumn(['requestedby','requestedby_type','status']);
        });
    }
};
