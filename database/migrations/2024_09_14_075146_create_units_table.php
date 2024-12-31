<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->string('actual_name');
            $table->string('short_name');
            $table->tinyInteger('allow_decimal');
            $table->integer('base_unit_id')->nullable()->index('units_base_unit_id_index');
            $table->decimal('base_unit_multiplier', 20, 4)->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            //$table->foreign('business_id', 'units_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
            //$table->foreign('created_by', 'units_created_by_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
