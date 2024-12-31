<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->string('location_id')->nullable();
            $table->string('name', 256);
            $table->string('razon_social', 100)->nullable();
            $table->text('landmark')->nullable();
            $table->string('country', 100);
            $table->string('state', 100);
            $table->string('city', 100);
            $table->char('zip_code', 7);
            $table->unsignedInteger('invoice_scheme_id');
            $table->unsignedInteger('invoice_layout_id');
            $table->boolean('print_receipt_on_invoice')->default(1);
            $table->enum('receipt_printer_type', ['browser', 'printer'])->default('browser');
            $table->integer('printer_id')->nullable();
            $table->string('mobile')->nullable();
            $table->string('alternate_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_label_1', 25)->nullable();
            $table->string('cuit', 25)->nullable();
            $table->string('punto_venta', 2)->nullable();
            $table->string('url_cert', 200)->nullable();
            $table->string('url_key', 200)->nullable();
            $table->string('tax_label_2', 50)->nullable();
            $table->string('tax_number_2', 50)->nullable();
            $table->date('afip_start_date')->nullable();
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
            $table->string('custom_field3')->nullable();
            $table->string('custom_field4')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            //$table->foreign('business_id', 'business_locations_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
            //$table->foreign('invoice_layout_id', 'business_locations_invoice_layout_id_foreign')->references('id')->on('invoice_layouts')->onDelete('cascade');
            //$table->foreign('invoice_scheme_id', 'business_locations_invoice_scheme_id_foreign')->references('id')->on('invoice_schemes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_locations');
    }
}
