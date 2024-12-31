<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('width', 8, 2)->nullable();
            $table->double('height', 8, 2)->nullable();
            $table->double('paper_width', 8, 2)->nullable();
            $table->double('paper_height', 8, 2)->nullable();
            $table->double('top_margin', 8, 2)->nullable();
            $table->double('left_margin', 8, 2)->nullable();
            $table->double('row_distance', 8, 2)->nullable();
            $table->double('col_distance', 8, 2)->nullable();
            $table->integer('stickers_in_one_row')->nullable();
            $table->boolean('is_default')->default(0);
            $table->boolean('is_continuous')->default(0);
            $table->integer('stickers_in_one_sheet')->nullable();
            $table->unsignedInteger('business_id')->nullable();
            $table->timestamps();
            
            //$table->foreign('business_id', 'barcodes_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barcodes');
    }
}
