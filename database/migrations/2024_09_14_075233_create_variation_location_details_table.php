<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationLocationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_location_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->index('variation_location_details_product_id_index');
            $table->unsignedInteger('product_variation_id')->index('variation_location_details_product_variation_id_index')->comment("id from product_variations table");
            $table->unsignedInteger('variation_id')->index('variation_location_details_variation_id_index');
            $table->unsignedInteger('location_id')->index('variation_location_details_location_id_foreign');
            $table->decimal('qty_available', 20, 4);
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
        Schema::dropIfExists('variation_location_details');
    }
}
