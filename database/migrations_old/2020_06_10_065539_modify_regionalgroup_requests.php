<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyRegionalgroupRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regionalgroups_requests', function (Blueprint $table) {
            $table->enum('topic', ['join', 'change', 'leave'])->after('account_id');
            $table->unsignedInteger('destination_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regionalgroups_requests', function (Blueprint $table) {
            $table->dropColumn('topic');
            $table->dropColumn('destination_id');
        });
    }
}
