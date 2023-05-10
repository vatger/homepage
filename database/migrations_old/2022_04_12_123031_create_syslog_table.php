<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyslogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syslog', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id')->nullable();
            $table->enum('type', ['log', 'exception'])->default('log');

            $table->string('path');
            $table->string('method');

            // Exceptions Only
            $table->longText('stack_trace')->nullable();
            $table->longText('message')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->timestamps();

            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syslog');
    }
}
