<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppearanceToAccountSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_account_settings', function (Blueprint $table) {
            $table
                ->boolean('dark_mode')
                ->default(0)
                ->after('forum_id');
            $table
                ->enum('color', ['default', 'cyan', 'red', 'green', 'purple', 'slateblue', 'skobleoff', 'yellow'])
                ->default('default')
                ->after('dark_mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_settings', function (Blueprint $table) {
            $table->dropColumn('dark_mode');
            $table->dropColumn('color');
        });
    }
}
