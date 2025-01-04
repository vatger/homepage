<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teamspeak_registrations', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->constrained('user_users', 'id');
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
    }

    public function down(): void
    {
        Schema::table('teamspeak_registration', function (Blueprint $table) {
            //
        });
    }
};
