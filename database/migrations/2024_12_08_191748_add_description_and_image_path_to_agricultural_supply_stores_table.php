<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionAndImagePathToAgriculturalSupplyStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agricultural_supply_stores', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name'); // Add 'description' column after 'name'
            $table->string('image_path')->nullable()->after('description'); // Add 'image_path' column after 'description'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agricultural_supply_stores', function (Blueprint $table) {
            $table->dropColumn(['description', 'image_path']); // Drop the columns if rolled back
        });
    }
}
