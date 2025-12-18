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
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contact_id')->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->text('contract_duraction')->nullable();
            $table->text('contact_before_end_of_contract')->nullable();
            $table->date('date_to_contact')->nullable();
            $table->text('contract_info')->nullable();
            $table->integer('number')->nullable();
            $table->text('connected_to_number')->nullable();
            $table->double('fee_monthly',10,2)->nullable();
            $table->double('discount',10,2)->nullable();
            $table->text('discount_duraction')->nullable();
            $table->double('price_total',10,2)->nullable();
            $table->date('contract_completion')->nullable();
            $table->text('contract_status')->nullable();
            $table->text('contract_feld1')->nullable();
            $table->text('contract_feld2')->nullable();
            $table->text('contract_feld3')->nullable();
            $table->text('contract_feld4')->nullable();
            $table->text('contract_feld5')->nullable();
            $table->text('contract_feld6')->nullable();
            $table->text('contract_feld7')->nullable();
            $table->text('contract_feld8')->nullable();
            $table->text('contract_feld9')->nullable();
            $table->text('contract_feld10')->nullable();
            $table->timestamps();

            $table->index('contact_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
