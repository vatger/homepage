<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // on hold until new satzung is more clear
        /*
         Schema::create('regionalgroup_tables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        */
        Schema::create('fir_firs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('mail');
            $table->foreignId('chief1')->constrained('user_users');
            $table->foreignId('chief2')->constrained('user_users');
            $table->timestamps();
        });

        Schema::create('fir_users', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->foreignId('fir_id')->constrained('fir_firs');
            $table->primary(['user_id', 'fir_id']);
            $table->timestamp('joined_at')->useCurrent();
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
        Schema::dropIfExists('fir_users');
        Schema::dropIfExists('fir_firs');
    }
};
