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
        Schema::create('cash_books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');            
            $table->integer('location_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->dateTime('transaction_date_time')->nullable();
            $table->year('transaction_year',4)->nullable();
            $table->integer('transaction_month')->nullable();
            $table->integer('transaction_day')->nullable();
            $table->date('transaction_date')->nullable();
            $table->time('transaction_time')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('cash_register_id')->nullable();
            $table->integer('cash_register_detail_id')->unsigned();
            $table->text('correction_description_cash_register')->nullable();
            $table->text('closing_note_cash_register')->nullable();
            $table->text('created_by_name')->nullable();
            $table->text('customer_name')->nullable();
            $table->text('description')->nullable();
            $table->text('status_cash_register')->nullable();
            $table->dateTime('cash_register_date')->nullable();
            $table->text('invoice_nr')->nullable();
            $table->text('invoice_url')->nullable();
            $table->double('amount', 22, 2)->nullable();
            $table->double('brutto_amount', 22, 2)->nullable();
            $table->double('netto_amount', 22, 2)->nullable();
            $table->double('discount_amount', 22, 2)->nullable();
            $table->bigInteger('numbering_cash')->nullable();
            $table->bigInteger('numbering_ec_card')->nullable();
            $table->bigInteger('numbering_credit')->nullable();
            $table->text('numbering_year')->nullable(); //Numierung Jahr
            $table->string('last_no_for_numbering_year', 5)->nullable(); //Last Number for Numierung Jahr
            $table->text('process')->nullable(); //Vorgang
            $table->text('transaction_type')->nullable();
            $table->text('pay_method')->nullable();
            $table->text('pay_method_de')->nullable();
            $table->text('payment_ref_no')->nullable();
            $table->double('sum_tax_rate', 22, 2)->nullable();
            $table->double('netto_tax_rate', 22, 2)->nullable();
            $table->double('brutto_tax_rate', 22, 2)->nullable();
            $table->text('tax_rate_as_text')->nullable();
            $table->double('sum_tax_rate_1', 22, 2)->nullable();
            $table->double('netto_tax_rate_1', 22, 2)->nullable();
            $table->double('brutto_tax_rate_1', 22, 2)->nullable();
            $table->text('tax_rate_as_text_1')->nullable();
            $table->double('sum_tax_rate_2', 22, 2)->nullable();
            $table->double('netto_tax_rate_2', 22, 2)->nullable();
            $table->double('brutto_tax_rate_2', 22, 2)->nullable();
            $table->text('tax_rate_as_text_2')->nullable();
            $table->double('sum_tax_rate_3', 22, 2)->nullable();
            $table->double('netto_tax_rate_3', 22, 2)->nullable();
            $table->double('brutto_tax_rate_3', 22, 2)->nullable();
            $table->text('tax_rate_as_text_3')->nullable();
            $table->double('sum_all_tax_rate', 22, 2)->nullable();
            $table->text('cash_book_field1')->nullable();
            $table->text('cash_book_field2')->nullable();
            $table->text('cash_book_field3')->nullable();
            $table->text('cash_book_field4')->nullable();
            $table->text('cash_book_field5')->nullable();
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
        Schema::dropIfExists('cash_books');
    }
};
