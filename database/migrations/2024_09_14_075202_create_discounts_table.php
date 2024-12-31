<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('business_id')->index('discounts_business_id_index');
            $table->integer('brand_id')->nullable()->index('discounts_brand_id_index');
            $table->integer('category_id')->nullable()->index('discounts_category_id_index');
            $table->integer('location_id')->nullable()->index('discounts_location_id_index');
            $table->integer('priority')->nullable()->index('discounts_priority_index');
            $table->string('discount_type')->nullable();
            $table->decimal('discount_amount', 20, 2)->default(0.00);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('applicable_in_spg')->default(0);
            $table->boolean('applicable_in_cg')->default(0);
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
        Schema::dropIfExists('discounts');
    }
}
