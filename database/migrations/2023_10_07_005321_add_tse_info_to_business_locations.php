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
            $table->string('fcc_connector_id')->nullable()->after('house_nr');
            $table->string('fcc_password')->nullable()->after('fcc_connector_id');
            $table->integer('fcc_port')->nullable()->after('fcc_password');
            $table->string('eas_code')->nullable()->after('fcc_port');
            $table->string('fcc_field1')->nullable()->after('eas_code');
            $table->string('fcc_field2')->nullable()->after('fcc_field1');
            $table->string('fcc_field3')->nullable()->after('fcc_field2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_locations', function (Blueprint $table) {
            //
        });
    }
};
