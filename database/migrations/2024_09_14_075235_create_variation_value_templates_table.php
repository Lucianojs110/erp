<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationValueTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_value_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index('variation_value_templates_name_index');
            $table->unsignedInteger('variation_template_id')->index('variation_value_templates_variation_template_id_index');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variation_value_templates');
    }
}
