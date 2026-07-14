<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTransactionPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_payments', function (Blueprint $table) {
            $table->string('cheque_bank')->nullable();
            $table->string('cheque_deferral_date')->nullable();
            $table->string('cheque_issue_date')->nullable();
            $table->string('cheque_type')->nullable();
            $table->string('cheque_amount')->nullable();
        });
    }

    public function down()
    {
        Schema::table('transaction_payments', function (Blueprint $table) {
            $table->dropColumn([
                'cheque_bank',
                'cheque_deferral_date',
                'cheque_issue_date',
                'cheque_type',
                'cheque_amount'
            ]);
        });
    }
}
