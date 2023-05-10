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
        Schema::table('membership_account_settings', function (Blueprint $table) {
            $table
                ->unsignedInteger('gitlab_id')
                ->nullable()
                ->after('forum_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_account_settings', function (Blueprint $table) {
            $table->dropColumn('gitlab_id');
        });
    }
};
