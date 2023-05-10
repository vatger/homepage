<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_bans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('author_id');
            $table->text('reason');
            $table->boolean('permanent')->default(true);
            $table->timestamp('banned_till')->nullable();
            $table->boolean('teamspeak')->default(true);
            $table->boolean('homepage')->default(true);
            $table->boolean('forum')->default(true);
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
        Schema::dropIfExists('membership_bans');
    }
}
