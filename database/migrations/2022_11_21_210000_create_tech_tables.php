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
        Schema::create('syslog', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('user_id')
                ->nullable()
                ->constrained('user_users')
                ->onDelete('SET NULL')
                ->onUpdate('NO ACTION');

            $table->enum('type', ['log', 'exception'])->default('log');
            $table->string('path');
            $table->string('method');

            // Exceptions Only
            $table->longText('stack_trace')->nullable();
            $table->longText('message')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
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
        Schema::dropIfExists('syslog');
    }
};
