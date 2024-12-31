<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('variation_id');
            $table->decimal('quantity', 20, 4);
            $table->decimal('pp_without_discount', 20, 2)->default(0.00)->comment("Purchase price before inline discounts");
            $table->decimal('discount_percent', 5, 2)->default(0.00)->comment("Inline discount percentage");
            $table->decimal('purchase_price', 20, 2)->nullable();
            $table->decimal('purchase_price_inc_tax', 20, 2)->default(0.00);
            $table->decimal('default_sell_price', 20, 2)->nullable();
            $table->decimal('item_tax', 20, 2)->nullable();
            $table->unsignedInteger('tax_id')->nullable();
            $table->decimal('quantity_sold', 20, 4)->default(0.0000);
            $table->decimal('quantity_adjusted', 20, 4)->default(0.0000);
            $table->decimal('quantity_returned', 20, 4)->default(0.0000);
            $table->date('mfg_date')->nullable();
            $table->date('exp_date')->nullable();
            $table->string('lot_number', 256)->nullable();
            $table->integer('sub_unit_id')->nullable()->index('purchase_lines_sub_unit_id_index');
            $table->timestamps();
            
            //$table->foreign('product_id', 'purchase_lines_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            //$table->foreign('tax_id', 'purchase_lines_tax_id_foreign')->references('id')->on('tax_rates')->onDelete('cascade');
            //$table->foreign('transaction_id', 'purchase_lines_transaction_id_foreign')->references('id')->on('transactions')->onDelete('cascade');
            //$table->foreign('variation_id', 'purchase_lines_variation_id_foreign')->references('id')->on('variations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_lines');
    }
}
