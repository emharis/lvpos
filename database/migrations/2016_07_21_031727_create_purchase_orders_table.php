<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('comments', 255)->nullable();
            $table->date('delivery_date')->nullable();
            $table->bigInteger('sub_total')->unsigned()->nullable();
            $table->bigInteger('shipping_china')->unsigned()->nullable();
            $table->bigInteger('shipping_jkt')->unsigned()->nullable();
            $table->bigInteger('biaya_xfer')->unsigned()->nullable();
            $table->bigInteger('total')->unsigned()->nullable();
            $table->bigInteger('irene_account')->unsigned()->nullable();
            $table->bigInteger('edwin_account')->unsigned()->nullable();
            $table->bigInteger('sicumi_account')->unsigned()->nullable();
            $table->date('transfer_date')->nullable();
            $table->decimal('rate', 12, 2)->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('purchase_orders');
    }
}
