<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('business_locations')->onDelete('cascade');
            $table->string('supplier_business_name')->nullable();
            $table->string('prefix', 191)->nullable();
            $table->string('first_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->string('email')->nullable();
            $table->string('street', 150)->nullable();
            $table->string('house_nr', 15)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable(); 
            $table->string('registration_token')->nullable();           
            $table->boolean('consent')->nullable();
            $table->boolean('consent_email')->nullable();
            $table->boolean('consent_mobile')->nullable();
            $table->boolean('consent_post')->nullable();
            $table->boolean('consent_messenger')->nullable();
            $table->boolean('dsvgo_accept')->nullable();
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
        Schema::dropIfExists('contacts_registrations');
    }
};
