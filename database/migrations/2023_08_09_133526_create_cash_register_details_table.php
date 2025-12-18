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
        Schema::create('cash_register_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('business_locations')->onDelete('cascade');
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('cash_register_active')->default(1);
            $table->boolean('tss_active')->default(1);
            $table->text('tss_serialnumber')->nullable();
            $table->text('fiscal_code_id')->nullable();
            $table->text('eas_code')->nullable();
            $table->text('tss_field1')->nullable();
            $table->text('tss_field2')->nullable();
            $table->text('tss_field3')->nullable();
            $table->text('tss_field4')->nullable();
            $table->text('tss_field5')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('cash_register_details');
    }
};
