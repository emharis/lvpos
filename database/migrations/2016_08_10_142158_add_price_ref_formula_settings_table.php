<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceRefFormulaSettingsTable extends Migration
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
            $table->string('price_ref_formula', 255)->default('%cost% * 1.15 * 1.4');
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
            $table->dropColumn('price_ref_formula');
        });
    }
}
