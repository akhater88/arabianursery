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
            $table->boolean('share_with_farmers')->default(false)->after('status');
            $table->boolean('share_with_nurseries')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seedling_services', function (Blueprint $table) {
            $table->dropColumn(['share_with_farmers','share_with_nurseries']);
        });
    }
};
