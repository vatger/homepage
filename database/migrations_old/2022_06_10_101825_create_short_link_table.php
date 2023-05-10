<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_link', function (Blueprint $table) {
            $table->id();
            $table->string('shortLink');
            $table->string('link');
            $table->unsignedInteger('creator');
            $table->boolean('active')->default(true);
            $table->dateTime('active_until')->nullable();
            $table->timestamps();

            $table
                ->foreign('creator')
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
        Schema::dropIfExists('short_link');
    }
};
