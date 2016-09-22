<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->date('spent_at')->nullable();
            $table->string('description')->nullable();
            $table->string('post')->nullable();
            $table->decimal('value', 16, 2)->nullable();
            $table->string('po_invoice_number');
            $table->string('remarks');
            $table->string('account', 30);
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
        Schema::drop('expenses');
    }
}
