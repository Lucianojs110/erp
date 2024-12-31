<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentTemporalStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_temporal_stocks', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('quantity');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('variation_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->integer('sales_commission_agent_id')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            //$table->foreign('product_id', 'agent_temporal_stocks_ibfk_1')->references('id')->on('products');
            //$table->foreign('variation_id', 'agent_temporal_stocks_ibfk_2')->references('id')->on('variations');
            //$table->foreign('sales_commission_agent_id', 'agent_temporal_stocks_ibfk_3')->references('id')->on('sales_commission_agents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_temporal_stocks');
    }
}
