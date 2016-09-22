<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSalesAddNoResiColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            //
            $table->string('no_resi')->nullable();
            $table->boolean('is_dropshipper')->default(0);
            $table->date('closing_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            //
            $table->dropColumn('no_resi');
            $table->dropColumn('is_dropshipper');
            $table->dropColumn('closing_date');
        });
    }
}
