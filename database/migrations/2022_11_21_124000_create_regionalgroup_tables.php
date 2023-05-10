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
        Schema::create('cm_communities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->string('mail');
            $table->timestamps();
        });

        Schema::create('cm_community_managers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->foreignId('community_id')->constrained('cm_communities');
            $table->primary(['user_id', 'community_id']);
            $table->string('description');
            $table->string('fir');
            $table->timestamps();
        });

        Schema::create('cm_community_users', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('user_users');
            $table->foreignId('community_id')->constrained('cm_communities');
            $table->primary(['user_id', 'community_id']);
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('fullmember_at')->nullable();
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
        //Schema::dropIfExists('regionalgroup_tables');
    }
};
