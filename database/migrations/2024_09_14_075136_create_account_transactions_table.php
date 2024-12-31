<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index('account_transactions_account_id_index');
            $table->enum('type', ['debit', 'credit']);
            $table->enum('sub_type', ['opening_balance', 'fund_transfer', 'deposit'])->nullable();
            $table->decimal('amount', 22, 4);
            $table->string('reff_no')->nullable();
            $table->dateTime('operation_date');
            $table->integer('created_by')->index('account_transactions_created_by_index');
            $table->integer('transaction_id')->nullable()->index('account_transactions_transaction_id_index');
            $table->integer('transaction_payment_id')->nullable()->index('account_transactions_transaction_payment_id_index');
            $table->integer('transfer_transaction_id')->nullable()->index('account_transactions_transfer_transaction_id_index');
            $table->text('note')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('account_transactions');
    }
}
