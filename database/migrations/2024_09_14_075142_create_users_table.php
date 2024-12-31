<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->char('surname', 10)->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->unique('users_username_unique');
            $table->string('email')->nullable();
            $table->string('password');
            $table->char('language', 4)->default('en');
            $table->char('contact_no', 15)->nullable();
            $table->text('address')->nullable();
            $table->rememberToken();
            $table->unsignedInteger('business_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->boolean('is_cmmsn_agnt')->default(0)->comment("DEPRECATED");
            $table->decimal('cmmsn_percent', 20, 2)->default(0.00)->comment("DEPRECATED");
            $table->boolean('selected_contacts')->default(0);
            $table->date('dob')->nullable();
            $table->enum('marital_status', ['married', 'unmarried', 'divorced'])->nullable();
            $table->char('blood_group', 10)->nullable();
            $table->char('contact_number', 20)->nullable();
            $table->string('fb_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('social_media_1')->nullable();
            $table->string('social_media_2')->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('current_address')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('custom_field_1')->nullable();
            $table->string('custom_field_2')->nullable();
            $table->string('custom_field_3')->nullable();
            $table->string('custom_field_4')->nullable();
            $table->longText('bank_details')->nullable();
            $table->string('id_proof_name')->nullable();
            $table->string('id_proof_number')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            //$table->foreign('business_id', 'users_business_id_foreign')->references('id')->on('business')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
