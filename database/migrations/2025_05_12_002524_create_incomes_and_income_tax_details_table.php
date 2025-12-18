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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->integer('location_id')->nullable();
            // $table->foreign('location_id')->references('id')->on('business_locations')->onDelete('cascade');
            $table->integer('cash_register_id')->nullable();
            $table->integer('cash_register_detail_id')->nullable();
            // $table->foreign('cash_register_detail_id')->references('id')->on('cash_register_details')->onDelete('cascade');
            $table->integer('created_by')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('type')->nullable();
            $table->dateTime('transaction_date');
            $table->decimal('final_total', 10, 2);
            $table->decimal('tax_total', 10, 2);
            $table->integer('categorie')->nullable();
            $table->integer('sub_categorie')->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('document')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('income_tax_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('income_id')->constrained('incomes')->onDelete('cascade');
            $table->integer('tax_rate_id')->nullable();
            $table->decimal('net_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('tax_rate', 10, 2);
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::create('income_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->string('code')->nullable();
            $table->integer('parent_id')->nullable();
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
        Schema::dropIfExists('income_tax_details');
        Schema::dropIfExists('income_categories');
        Schema::dropIfExists('incomes');        
    }
};
