<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSaleitemsAddSubTotalPriceRef extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_items', function (Blueprint $table) {
            //
            $table->decimal('total_price_ref', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_items', function (Blueprint $table) {
            //
            $table->dropColumn('total_price_ref')->nullable();
        });
    }
}
