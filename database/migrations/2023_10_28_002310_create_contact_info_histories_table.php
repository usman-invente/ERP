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
        Schema::create('contact_info_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->integer('location_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('contract_id')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('details')->nullable();
            $table->text('ip_address')->nullable();
            $table->text('type')->nullable();
            $table->text('consent')->nullable();
            $table->text('info_1')->nullable();
            $table->text('info_2')->nullable();
            $table->date('change_date')->nullable();
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
        Schema::dropIfExists('contact_info_histories');
    }
};
