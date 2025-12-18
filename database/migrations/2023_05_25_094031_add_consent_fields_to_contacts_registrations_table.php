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
        Schema::table('contacts_registrations', function (Blueprint $table) {
            $table->boolean('consent_field2')->nullable()->after('dsvgo_accept');
            $table->boolean('consent_field3')->nullable()->after('consent_field2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts_registrations', function (Blueprint $table) {
            //
        });
    }
};
