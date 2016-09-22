<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAtPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            //
            $table->string('bill_acccount')->after('sub_total');
            $table->decimal('bill_amount', 18, 2)->after('sub_total');
            $table->decimal('additional_charges', 18, 2)->after('sub_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            //
            $table->dropColumn('bill_acccount');
            $table->dropColumn('bill_amount');
            $table->dropColumn('additional_charges');
        });
    }
}
