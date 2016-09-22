<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSaleTempsAddPriceRef extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_temps', function (Blueprint $table) {
            //
            $table->decimal('price_ref', 15,2)->default('0.00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_temps', function (Blueprint $table) {
            //
            $table->dropColumn('price_ref');
        });
    }
}