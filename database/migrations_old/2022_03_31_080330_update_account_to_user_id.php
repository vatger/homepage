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
        Schema::table('regionalgroups_account_regionalgroup', function (Blueprint $table) {
            $table->renameColumn('account_id', 'user_id');
        });

        Schema::table('regionalgroups_mentors', function (Blueprint $table) {
            $table->renameColumn('account_id', 'user_id');
        });

        Schema::table('regionalgroups_navigators', function (Blueprint $table) {
            $table->renameColumn('account_id', 'user_id');
        });

        Schema::table('regionalgroups_eventler', function (Blueprint $table) {
            $table->renameColumn('account_id', 'user_id');
        });

        Schema::table('regionalgroups_requests', function (Blueprint $table) {
            $table->renameColumn('account_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regionalgroups_account_regionalgroup', function (Blueprint $table) {
            $table->renameColumn('user_id', 'account_id');
        });

        Schema::table('regionalgroups_mentors', function (Blueprint $table) {
            $table->renameColumn('user_id', 'account_id');
        });

        Schema::table('regionalgroups_navigators', function (Blueprint $table) {
            $table->renameColumn('user_id', 'account_id');
        });

        Schema::table('regionalgroups_eventler', function (Blueprint $table) {
            $table->renameColumn('user_id', 'account_id');
        });

        Schema::table('regionalgroups_requests', function (Blueprint $table) {
            $table->renameColumn('user_id', 'account_id');
        });
    }
};
