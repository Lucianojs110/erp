<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index('variations_name_index');
            $table->unsignedInteger('product_id')->index('variations_product_id_foreign');
            $table->string('sub_sku')->nullable()->index('variations_sub_sku_index');
            $table->unsignedInteger('product_variation_id')->index('variations_product_variation_id_foreign');
            $table->integer('variation_value_id')->nullable()->index('variations_variation_value_id_index');
            $table->decimal('default_purchase_price', 20, 5)->nullable();
            $table->decimal('dpp_inc_tax', 20, 5)->default(0.00000);
            $table->decimal('profit_percent', 20, 2)->default(0.00);
            $table->decimal('default_sell_price', 20, 5)->nullable();
            $table->decimal('sell_price_inc_tax', 20, 5)->nullable();
            $table->decimal('cantidadMayorista', 10, 2)->nullable();
            $table->decimal('precioMayorista', 10, 5)->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variations');
    }
}
