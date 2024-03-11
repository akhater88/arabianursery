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
            $table->decimal('tray_shared_price')->nullable(true)->after('share_with_farmers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seedling_services', function (Blueprint $table) {
            $table->dropColumn('tray_shared_price');
        });
    }
};
