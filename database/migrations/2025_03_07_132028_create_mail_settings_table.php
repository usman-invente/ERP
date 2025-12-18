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
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Falls jeder User eigene SMTP-Daten hat
            $table->string('mailer')->default('smtp');
            $table->string('host');
            $table->integer('port')->default(587);
            $table->string('username');
            $table->string('password'); // Besser verschlüsselt speichern!
            $table->string('encryption')->default('tls');
            $table->string('from_address');
            $table->string('from_name')->nullable();
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
        Schema::dropIfExists('mail_settings');
    }
};
