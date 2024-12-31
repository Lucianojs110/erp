<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationGroupPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_group_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('variation_id');
            $table->unsignedInteger('price_group_id');
            $table->decimal('price_inc_tax', 20, 2);
            $table->timestamps();
            
            //$table->foreign('price_group_id', 'variation_group_prices_price_group_id_foreign')->references('id')->on('selling_price_groups')->onDelete('cascade');
            //$table->foreign('variation_id', 'variation_group_prices_variation_id_foreign')->references('id')->on('variations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variation_group_prices');
    }
}
