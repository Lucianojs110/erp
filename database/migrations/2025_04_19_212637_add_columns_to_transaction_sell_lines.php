<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTransactionSellLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->float("width",53,2)->nullable()->default(null);
            $table->float("height",53,2)->nullable()->default(null);
            $table->integer("quantity_of_sheets")->nullable()->default(null);
            $table->float("total_unities",53,2)->nullable()->default(null);
            $table->float("price_per_unit",53,2)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->dropColumn("width");
            $table->dropColumn("height");
            $table->dropColumn("quantity_of_sheets");
            $table->dropColumn("total_unities");
            $table->dropColumn("price_per_unit");
        });
    }
}
