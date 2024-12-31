<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('waiter_id')->nullable()->index('bookings_waiter_id_index');
            $table->unsignedInteger('table_id')->nullable()->index('bookings_table_id_index');
            $table->integer('correspondent_id')->nullable();
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('location_id')->index('bookings_location_id_index');
            $table->dateTime('booking_start');
            $table->dateTime('booking_end');
            $table->unsignedInteger('created_by');
            $table->enum('booking_status', ['booked', 'completed', 'cancelled']);
            $table->text('booking_note')->nullable();
            $table->timestamps();
            
            //$table->foreign('business_id', 'bookings_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
            //$table->foreign('contact_id', 'bookings_contact_id_foreign')->references('id')->on('contacts')->onDelete('cascade');
            //$table->foreign('created_by', 'bookings_created_by_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
