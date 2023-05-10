<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImplementForumGroupAssignments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forumgroup_group', function (Blueprint $table) {
            $table->renameColumn('group_id', 'role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forumgroup_group', function (Blueprint $table) {
            $table->renameColumn('role_id', 'group_id');
        });
    }
}
