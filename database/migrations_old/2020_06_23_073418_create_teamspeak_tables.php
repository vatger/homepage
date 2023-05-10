<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamspeakTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teamspeak_registration', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table
                ->integer('account_id')
                ->unsigned()
                ->index();
            $table->string('registration_ip');
            $table
                ->string('last_ip')
                ->nullable()
                ->default('0.0.0.0');
            $table->timestamp('last_login')->nullable();
            $table->string('last_os')->nullable();
            $table->string('uid')->nullable();
            $table
                ->smallInteger('dbid')
                ->unsigned()
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('teamspeak_confirmation', function (Blueprint $table) {
            $table
                ->integer('registration_id')
                ->primary()
                ->unsigned();
            $table->string('privilege_key', 50);
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
        Schema::dropIfExists('teamspeak_registration');
        Schema::dropIfExists('teamspeak_confirmation');
    }
}
