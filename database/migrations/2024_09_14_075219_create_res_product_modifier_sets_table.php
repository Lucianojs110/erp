<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResProductModifierSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('res_product_modifier_sets', function (Blueprint $table) {
            $table->unsignedInteger('modifier_set_id');
            $table->unsignedInteger('product_id')->comment("Table use to store the modifier sets applicable for a product");
            
            //$table->foreign('modifier_set_id', 'res_product_modifier_sets_modifier_set_id_foreign')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('res_product_modifier_sets');
    }
}
