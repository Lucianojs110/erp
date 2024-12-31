<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index('products_name_index');
            $table->unsignedInteger('business_id');
            $table->enum('type', ['single', 'variable', 'modifier'])->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('brand_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('sub_category_id')->nullable();
            $table->unsignedInteger('tax')->nullable();
            $table->enum('tax_type', ['inclusive', 'exclusive']);
            $table->boolean('enable_stock')->default(0);
            $table->integer('alert_quantity')->default(0);
            $table->string('sku');
            $table->enum('barcode_type', ['C39', 'C128', 'EAN13', 'EAN8', 'UPCA', 'UPCE'])->default('C128');
            $table->decimal('expiry_period', 4, 2)->nullable();
            $table->enum('expiry_period_type', ['days', 'months'])->nullable();
            $table->boolean('enable_sr_no')->default(0);
            $table->string('weight')->nullable();
            $table->string('product_custom_field1')->nullable();
            $table->string('product_custom_field2')->nullable();
            $table->string('product_custom_field3')->nullable();
            $table->string('product_custom_field4')->nullable();
            $table->string('image')->nullable();
            $table->text('product_description')->nullable();
            $table->unsignedInteger('created_by');
            $table->boolean('is_inactive')->default(0);
            $table->boolean('hasMayorista')->default(0);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            
            //$table->foreign('brand_id', 'products_brand_id_foreign')->references('id')->on('brands')->onDelete('cascade');
            //$table->foreign('business_id', 'products_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
            //$table->foreign('category_id', 'products_category_id_foreign')->references('id')->on('categories')->onDelete('cascade');
            //$table->foreign('created_by', 'products_created_by_foreign')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('sub_category_id', 'products_sub_category_id_foreign')->references('id')->on('categories')->onDelete('cascade');
            //$table->foreign('tax', 'products_tax_foreign')->references('id')->on('tax_rates');
            //$table->foreign('unit_id', 'products_unit_id_foreign')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
