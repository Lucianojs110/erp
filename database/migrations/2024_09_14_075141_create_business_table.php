<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 256);
            $table->string('business_name', 100)->nullable();
            $table->unsignedInteger('currency_id');
            $table->date('start_date')->nullable();
            $table->integer('invoice_register_limit')->nullable();
            $table->integer('invoice_limit_card')->nullable();
            $table->unsignedInteger('default_sales_tax')->nullable();
            $table->double('default_profit_percent', 5, 2)->default(0.00);
            $table->unsignedInteger('owner_id');
            $table->string('time_zone')->default('Asia/Kolkata');
            $table->boolean('fy_start_month')->default(1);
            $table->enum('accounting_method', ['fifo', 'lifo', 'avco'])->default('fifo');
            $table->decimal('default_sales_discount', 20, 2)->nullable();
            $table->enum('sell_price_tax', ['includes', 'excludes'])->default('includes');
            $table->string('logo')->nullable();
            $table->string('sku_prefix')->nullable();
            $table->boolean('enable_product_expiry')->default(0);
            $table->enum('expiry_type', ['add_expiry', 'add_manufacturing'])->default('add_expiry');
            $table->enum('on_product_expiry', ['keep_selling', 'stop_selling', 'auto_delete'])->default('keep_selling');
            $table->integer('stop_selling_before')->comment("Stop selling expied item n days before expiry");
            $table->boolean('enable_tooltip')->default(1);
            $table->boolean('purchase_in_diff_currency')->default(0)->comment("Allow purchase to be in different currency then the business currency");
            $table->unsignedInteger('purchase_currency_id')->nullable();
            $table->decimal('p_exchange_rate', 20, 3)->default(1.000);
            $table->unsignedInteger('transaction_edit_days')->default(30);
            $table->unsignedInteger('stock_expiry_alert_days')->default(30);
            $table->text('keyboard_shortcuts')->nullable();
            $table->text('pos_settings')->nullable();
            $table->boolean('enable_brand')->default(1);
            $table->boolean('enable_category')->default(1);
            $table->boolean('enable_sub_category')->default(1);
            $table->boolean('enable_price_tax')->default(1);
            $table->boolean('enable_purchase_status')->default(1);
            $table->boolean('enable_lot_number')->default(0);
            $table->integer('default_unit')->nullable();
            $table->boolean('enable_racks')->default(0);
            $table->boolean('enable_row')->default(0);
            $table->boolean('enable_position')->default(0);
            $table->boolean('enable_editing_product_from_purchase')->default(1);
            $table->enum('sales_cmsn_agnt', ['logged_in_user', 'user', 'cmsn_agnt'])->nullable();
            $table->boolean('item_addition_method')->default(1);
            $table->boolean('enable_inline_tax')->default(1);
            $table->enum('currency_symbol_placement', ['before', 'after'])->default('before');
            $table->text('enabled_modules')->nullable();
            $table->string('date_format')->default('m/d/Y');
            $table->enum('time_format', ['12', '24'])->default('24');
            $table->text('ref_no_prefixes')->nullable();
            $table->char('theme_color', 20)->nullable();
            $table->integer('created_by')->nullable();
            $table->text('email_settings')->nullable();
            $table->text('sms_settings')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            
            //$table->foreign('currency_id', 'business_currency_id_foreign')->references('id')->on('currencies');
            //$table->foreign('default_sales_tax', 'business_default_sales_tax_foreign')->references('id')->on('tax_rates');
            //$table->foreign('owner_id', 'business_owner_id_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business');
    }
}
