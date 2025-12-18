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
        Schema::table('business_locations', function (Blueprint $table) {
            $table->string('number_business_location',4)->nullable()->default('0001')->after('fcc_field3');
        });

        Schema::table('cash_register_details', function (Blueprint $table) {
            $table->string('number_of_cash_register_detail',4)->nullable()->default('01')->after('fcc_access_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
