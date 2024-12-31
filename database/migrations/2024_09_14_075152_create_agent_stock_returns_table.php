<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentStockReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_stock_returns', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->unsignedInteger('return_number')->nullable();
            $table->integer('agent_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('variation_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->integer('quantity');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            //$table->foreign('agent_id', 'agent_stock_returns_ibfk_1')->references('id')->on('sales_commission_agents');
            //$table->foreign('product_id', 'agent_stock_returns_ibfk_2')->references('id')->on('products');
            //$table->foreign('variation_id', 'agent_stock_returns_ibfk_3')->references('id')->on('variations');
            //$table->foreign('location_id', 'agent_stock_returns_ibfk_4')->references('id')->on('business_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_stock_returns');
    }
}
