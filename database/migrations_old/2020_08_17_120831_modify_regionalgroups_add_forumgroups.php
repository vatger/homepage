<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyRegionalgroupsAddForumgroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regionalgroups_regionalgroups', function (Blueprint $table) {
            $table
                ->unsignedInteger('staff_group_id')
                ->default(2)
                ->after('deputy_id'); // Local group id
            $table
                ->unsignedInteger('voting_group_id')
                ->default(2)
                ->after('staff_group_id'); // Local group id
            $table
                ->unsignedInteger('mentor_group_id')
                ->default(2)
                ->after('voting_group_id'); // Local group id
            $table
                ->unsignedInteger('navler_group_id')
                ->default(2)
                ->after('mentor_group_id'); // Local group id
            $table
                ->unsignedInteger('eventler_group_id')
                ->default(2)
                ->after('navler_group_id'); // Local group id
            $table
                ->unsignedInteger('member_group_id')
                ->default(2)
                ->after('eventler_group_id'); // full members group
            $table
                ->unsignedInteger('guest_group_id')
                ->default(2)
                ->after('member_group_id'); // guest member group
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regionalgroups_regionalgroups', function (Blueprint $table) {
            $table->dropColumn('staff_group_id');
            $table->dropColumn('voting_group_id');
            $table->dropColumn('mentor_group_id');
            $table->dropColumn('navler_group_id');
            $table->dropColumn('eventler_group_id');
            $table->dropColumn('member_group_id');
            $table->dropColumn('guest_group_id');
        });
    }
}
