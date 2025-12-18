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
        Schema::table('cash_register_details', function (Blueprint $table) {
            $table->text('seriennumber')->nullable()->after('eas_code');
            $table->text('fcc_access_token')->nullable()->after('seriennumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_register_details', function (Blueprint $table) {
            //
        });
    }
};
