<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->string('name');
            $table->double('amount', 8, 2);
            $table->boolean('is_tax_group')->default(0);
            $table->unsignedInteger('created_by');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            //$table->foreign('business_id', 'tax_rates_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
            //$table->foreign('created_by', 'tax_rates_created_by_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_rates');
    }
}
