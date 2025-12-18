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
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->decimal('product_purchase_price', 22, 2)->after('tax_id')->nullable();
            $table->decimal('product_purchase_price_inc_tax', 22, 2)->after('product_purchase_price')->nullable();
            $table->decimal('product_sell_price_plan', 22, 2)->after('product_purchase_price_inc_tax')->nullable();
            $table->decimal('product_sell_price_inc_tax_plan', 22, 2)->after('product_sell_price_plan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->dropColumn('product_purchase_price');
            $table->dropColumn('product_purchase_price_inc_tax');
            $table->dropColumn('product_sell_price_plan');
            $table->dropColumn('product_sell_price_inc_tax_plan');
        });
    }
};
