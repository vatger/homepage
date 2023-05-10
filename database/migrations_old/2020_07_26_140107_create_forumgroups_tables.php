<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumgroupsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forumgroups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('forum_id'); // GROUP ID ON THE FORUM
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('forumgroup_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('group_id'); // Local group id
            $table->unsignedInteger('forumgroup_id'); // Id of the forum-group in our database
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forumgroup_group');
        Schema::dropIfExists('forumgroups');
    }
}
