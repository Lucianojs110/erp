<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('res_table_id')->nullable()->comment("fields to restaurant module");
            $table->unsignedInteger('res_waiter_id')->nullable()->comment("fields to restaurant module");
            $table->enum('res_order_status', ['received', 'cooked', 'served'])->nullable();
            $table->enum('type', ['purchase', 'sell', 'expense', 'stock_adjustment', 'sell_transfer', 'purchase_transfer', 'opening_stock', 'sell_return', 'opening_balance', 'purchase_return'])->nullable()->index('transactions_type_index');
            $table->string('sub_type', 20)->nullable()->index('transactions_sub_type_index');
            $table->enum('status', ['received', 'pending', 'ordered', 'draft', 'final']);
            $table->boolean('is_quotation')->default(0);
            $table->enum('payment_status', ['paid', 'due', 'partial'])->nullable();
            $table->enum('adjustment_type', ['normal', 'abnormal'])->nullable();
            $table->unsignedInteger('contact_id')->nullable();
            $table->integer('customer_group_id')->nullable()->comment("used to add customer group while selling");
            $table->string('invoice_no')->nullable();
            $table->string('type_invoice', 1)->nullable();
            $table->string('ref_no')->nullable();
            $table->string('subscription_no')->nullable();
            $table->dateTime('transaction_date')->index('transactions_transaction_date_index');
            $table->decimal('total_before_tax', 20, 2)->default(0.00);
            $table->unsignedInteger('tax_id')->nullable();
            $table->decimal('tax_amount', 20, 2)->default(0.00);
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->string('discount_amount', 10)->nullable();
            $table->string('shipping_details')->nullable();
            $table->decimal('shipping_charges', 20, 2)->default(0.00);
            $table->text('additional_notes')->nullable();
            $table->text('staff_note')->nullable();
            $table->decimal('final_total', 20, 2)->default(0.00);
            $table->unsignedInteger('expense_category_id')->nullable();
            $table->unsignedInteger('expense_for')->nullable();
            $table->integer('commission_agent')->nullable();
            $table->string('document')->nullable();
            $table->boolean('is_direct_sale')->default(0);
            $table->boolean('is_suspend')->default(0);
            $table->decimal('exchange_rate', 20, 3)->default(1.000);
            $table->decimal('total_amount_recovered', 20, 2)->nullable()->comment("Used for stock adjustment.");
            $table->integer('transfer_parent_id')->nullable();
            $table->integer('return_parent_id')->nullable()->index('transactions_return_parent_id_index');
            $table->integer('opening_stock_product_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->text('order_addresses')->nullable();
            $table->boolean('is_recurring')->default(0);
            $table->double('recur_interval', 8, 2)->nullable();
            $table->enum('recur_interval_type', ['days', 'months', 'years'])->nullable();
            $table->integer('recur_repetitions')->nullable();
            $table->dateTime('recur_stopped_on')->nullable();
            $table->integer('recur_parent_id')->nullable();
            $table->string('invoice_token')->nullable();
            $table->integer('pay_term_number')->nullable();
            $table->enum('pay_term_type', ['days', 'months'])->nullable();
            $table->integer('selling_price_group_id')->nullable();
            $table->string('cae', 30)->nullable();
            $table->date('exp_cae')->nullable();
            $table->date('afip_invoice_date')->nullable();
            $table->string('num_invoice_afip', 20)->nullable();
            $table->string('qrCode', 500)->nullable();
            $table->decimal('iva10', 10, 2);
            $table->decimal('iva21', 10, 2);
            $table->decimal('iva27', 10, 2);
            $table->timestamps();
            
            //$table->foreign('business_id', 'transactions_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
            //$table->foreign('contact_id', 'transactions_contact_id_foreign')->references('id')->on('contacts')->onDelete('cascade');
            //$table->foreign('created_by', 'transactions_created_by_foreign')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('expense_category_id', 'transactions_expense_category_id_foreign')->references('id')->on('expense_categories')->onDelete('cascade');
            //$table->foreign('expense_for', 'transactions_expense_for_foreign')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('location_id', 'transactions_location_id_foreign')->references('id')->on('business_locations');
            //$table->foreign('tax_id', 'transactions_tax_id_foreign')->references('id')->on('tax_rates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
