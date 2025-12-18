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
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('street', 150)->nullable()->after('tax_number');
            $table->string('house_nr', 15)->nullable()->after('street');
            $table->boolean('consent_email')->nullable()->after('consent');
            $table->boolean('consent_mobile')->nullable()->after('consent_email');
            $table->boolean('consent_post')->nullable()->after('consent_mobile');
            $table->boolean('consent_messenger')->nullable()->after('consent_post');
            $table->boolean('consent_field1')->nullable()->after('consent_messenger');
            $table->boolean('consent_field2')->nullable()->after('consent_field1');
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
        Schema::table('contacts', function (Blueprint $table) {
            //
        });
    }
};
