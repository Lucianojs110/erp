<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegisterTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_register_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cash_register_id');
            $table->decimal('amount', 20, 2)->default(0.00);
            $table->decimal('guarani', 10, 2)->default(0.00);
            $table->decimal('reales', 10, 2)->default(0.00);
            $table->decimal('dolar', 10, 2)->default(0.00);
            $table->decimal('euro', 10, 2)->default(0.00);
            $table->enum('pay_method', ['cash', 'card', 'cheque', 'bank_transfer', 'custom_pay_1', 'custom_pay_2', 'custom_pay_3', 'other'])->nullable();
            $table->enum('type', ['debit', 'credit']);
            $table->enum('transaction_type', ['initial', 'sell', 'transfer', 'refund', 'expense']);
            $table->integer('transaction_id')->nullable()->index('cash_register_transactions_transaction_id_index');
            $table->timestamps();
            
            //$table->foreign('cash_register_id', 'cash_register_transactions_cash_register_id_foreign')->references('id')->on('cash_registers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_register_transactions');
    }
}
