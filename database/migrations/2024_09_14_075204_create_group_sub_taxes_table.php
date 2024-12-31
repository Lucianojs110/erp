<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupSubTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_sub_taxes', function (Blueprint $table) {
            $table->unsignedInteger('group_tax_id');
            $table->unsignedInteger('tax_id');
            
            //$table->foreign('group_tax_id', 'group_sub_taxes_group_tax_id_foreign')->references('id')->on('tax_rates')->onDelete('cascade');
            //$table->foreign('tax_id', 'group_sub_taxes_tax_id_foreign')->references('id')->on('tax_rates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_sub_taxes');
    }
}
