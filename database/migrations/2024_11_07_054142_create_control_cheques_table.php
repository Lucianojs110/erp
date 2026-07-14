<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheques', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedInteger('business_id');
            $table->enum('type', ['comun', 'diferido', 'electronico']);
            $table->string('number')->unique();
            $table->string('bank');
            $table->date('issue_date');
            $table->date('deferral_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('state', ['en_cartera', 'endosado', 'depositado', 'aceptado_pendiente', 'en_custodia', 'rechazado']);
            $table->integer('payment_for')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('direction', ['emitido', 'recibido']);
            $table->timestamp('paid_on')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('cheques');
    }
};
