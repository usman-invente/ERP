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
            $table->boolean('consent_field4')->nullable()->after('consent_field3');
            $table->boolean('consent_field5')->nullable()->after('consent_field4');
            $table->boolean('consent_cash')->nullable()->after('consent_field5');
            $table->text('bank')->nullable()->after('consent_cash');
            $table->text('iban')->nullable()->after('bank');
            $table->text('bic')->nullable()->after('iban');
            $table->text('paypal')->nullable()->after('bic');
            $table->text('pay_type1')->nullable()->after('paypal');
            $table->text('pay_type2')->nullable()->after('pay_type1');
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
