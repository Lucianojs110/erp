<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->float("price_per_unit",53,2)->nullable()->default(null);
            $table->float("width",53,2)->nullable()->default(null);
            $table->float("height",53,2)->nullable()->default(null);
          //  $table->integer("quantity_of_package")->nulable();
            $table->integer("quantity_of_sheets")->nullable()->default(null);
          //  $table->float("total_unities",53,2)->nulable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn("price_per_unit");
            $table->dropColumn("width");
            $table->dropColumn("height");
          //  $table->dropColumn("quantity_of_package");
            $table->dropColumn("quantity_of_sheets");
          //  $table->dropColumn("total_unities");
        });
    }
}
