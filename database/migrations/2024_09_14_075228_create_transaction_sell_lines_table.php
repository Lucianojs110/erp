<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionSellLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_sell_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('variation_id');
            $table->decimal('quantity', 20, 4);
            $table->decimal('quantity_returned', 20, 4)->default(0.0000);
            $table->decimal('unit_price_before_discount', 20, 2)->default(0.00);
            $table->decimal('unit_price', 20, 2)->nullable();
            $table->enum('line_discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('line_discount_amount', 20, 2)->default(0.00);
            $table->decimal('unit_price_inc_tax', 20, 2)->nullable();
            $table->decimal('item_tax', 20, 2)->nullable();
            $table->unsignedInteger('tax_id')->nullable();
            $table->integer('discount_id')->nullable();
            $table->integer('lot_no_line_id')->nullable();
            $table->text('sell_line_note')->nullable();
            $table->integer('res_service_staff_id')->nullable();
            $table->string('res_line_order_status')->nullable();
            $table->integer('parent_sell_line_id')->nullable();
            $table->integer('sub_unit_id')->nullable();
            $table->timestamps();
            
            //$table->foreign('product_id', 'transaction_sell_lines_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            //$table->foreign('tax_id', 'transaction_sell_lines_tax_id_foreign')->references('id')->on('tax_rates')->onDelete('cascade');
            //$table->foreign('transaction_id', 'transaction_sell_lines_transaction_id_foreign')->references('id')->on('transactions')->onDelete('cascade');
            //$table->foreign('variation_id', 'transaction_sell_lines_variation_id_foreign')->references('id')->on('variations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_sell_lines');
    }
}
