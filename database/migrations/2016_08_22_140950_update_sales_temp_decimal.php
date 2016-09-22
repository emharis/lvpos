<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSalesTempDecimal extends Migration
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
            $table->decimal('cost_price', 20, 2)->change();
            $table->decimal('selling_price', 20, 2)->change();
            $table->decimal('total_cost', 20, 2)->change();
            $table->decimal('total_selling', 20, 2)->change();
            $table->decimal('price_ref', 20, 2)->change();
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
        });
    }
}
