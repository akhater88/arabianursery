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
        Schema::table('seedling_services', function (Blueprint $table) {
            $table->boolean('reserved')->default(false)->after('share_with_farmers');
            $table->unsignedBigInteger('reserved_from')->default(0)->after('reserved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seedling_services', function (Blueprint $table) {
            $table->dropColumn(['reserved', 'reserved_from']);
        });
    }
};
