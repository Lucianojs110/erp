<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->enum('type', ['supplier', 'customer', 'both']);
            $table->string('supplier_business_name')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact_id')->nullable();
            $table->string('tipo_doc', 45)->nullable();
            $table->string('tax_number')->nullable();
            $table->string('iva', 30)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('landmark')->nullable();
            $table->string('mobile');
            $table->string('landline')->nullable();
            $table->string('alternate_number')->nullable();
            $table->integer('pay_term_number')->nullable();
            $table->enum('pay_term_type', ['days', 'months'])->nullable();
            $table->decimal('credit_limit', 20, 2)->nullable();
            $table->unsignedInteger('created_by');
            $table->boolean('is_default')->default(0);
            $table->integer('customer_group_id')->nullable();
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
            $table->string('custom_field3')->nullable();
            $table->string('custom_field4')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            //$table->foreign('business_id', 'contacts_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
            //$table->foreign('created_by', 'contacts_created_by_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
