<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSettingsAddShipAndTargetMargin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutapos_settings', function (Blueprint $table) {
            //
            $table->decimal('shipping_factor')->default('1.15');
            $table->decimal('margin_factor')->default('1.4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutapos_settings', function (Blueprint $table) {
            //
            $table->dropColumn('shipping_factor');
            $table->dropColumn('margin_factor');
            
        });
    }
}
