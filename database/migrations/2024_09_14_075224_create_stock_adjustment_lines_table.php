<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustment_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('variation_id');
            $table->decimal('quantity', 20, 4);
            $table->decimal('unit_price', 20, 2)->nullable()->comment("Last purchase unit price");
            $table->integer('removed_purchase_line')->nullable();
            $table->integer('lot_no_line_id')->nullable();
            $table->timestamps();
            
            //$table->foreign('product_id', 'stock_adjustment_lines_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            //$table->foreign('transaction_id', 'stock_adjustment_lines_transaction_id_foreign')->references('id')->on('transactions')->onDelete('cascade');
            //$table->foreign('variation_id', 'stock_adjustment_lines_variation_id_foreign')->references('id')->on('variations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustment_lines');
    }
}
