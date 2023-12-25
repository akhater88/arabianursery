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
        Schema::create('agricultural_supply_store_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('country_code')->nullable();
            $table->string('mobile_number')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->rememberToken();

            $table->nullableMorphs('added_by');
            $table->foreignId('agricultural_supply_store_id')->nullable()->constrained('agricultural_supply_stores', 'id', 'agricultural_supply_store_id_foreign');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agricultural_supply_store_users');
    }
};
