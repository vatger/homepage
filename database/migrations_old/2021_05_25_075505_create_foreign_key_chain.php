<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeyChain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_account_data', function (Blueprint $table) {
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('membership_account_settings', function (Blueprint $table) {
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('membership_notes', function (Blueprint $table) {
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('author_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('membership_bans', function (Blueprint $table) {
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('author_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('bookings_atc', function (Blueprint $table) {
            $table->unsignedBigInteger('station_id')->change();

            // Make sure that the station_id is an UNSIGNED BIGINT before this
            $table
                ->foreign('station_id')
                ->references('id')
                ->on('navigation_stations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('controller_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('regionalgroups_regionalgroups', function (Blueprint $table) {
            $table
                ->foreign('chief_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('deputy_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('regionalgroups_account_regionalgroup', function (Blueprint $table) {
            $table->unsignedBigInteger('regionalgroup_id')->change();

            $table
                ->foreign('regionalgroup_id')
                ->references('id')
                ->on('regionalgroups_regionalgroups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('regionalgroups_mentors', function (Blueprint $table) {
            $table->unsignedBigInteger('regionalgroup_id')->change();

            $table
                ->foreign('regionalgroup_id')
                ->references('id')
                ->on('regionalgroups_regionalgroups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('regionalgroups_navigators', function (Blueprint $table) {
            $table->unsignedBigInteger('regionalgroup_id')->change();

            $table
                ->foreign('regionalgroup_id')
                ->references('id')
                ->on('regionalgroups_regionalgroups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('regionalgroups_eventler', function (Blueprint $table) {
            $table->unsignedBigInteger('regionalgroup_id')->change();

            $table
                ->foreign('regionalgroup_id')
                ->references('id')
                ->on('regionalgroups_regionalgroups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('regionalgroups_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('regionalgroup_id')->change();

            $table
                ->foreign('regionalgroup_id')
                ->references('id')
                ->on('regionalgroups_regionalgroups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('navigation_aerodrome_regionalgroup', function (Blueprint $table) {
            $table->unsignedBigInteger('regionalgroup_id')->change();
            $table
                ->foreign('aerodrome_id')
                ->references('id')
                ->on('navigation_aerodromes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('regionalgroup_id')
                ->references('id')
                ->on('regionalgroups_regionalgroups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('navigation_aerodrome_station', function (Blueprint $table) {
            $table->unsignedBigInteger('station_id')->change();
            $table
                ->foreign('aerodrome_id')
                ->references('id')
                ->on('navigation_aerodromes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('station_id')
                ->references('id')
                ->on('navigation_stations')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('navigation_aerodrome_chart', function (Blueprint $table) {
            $table->unsignedBigInteger('chart_id')->change();
            $table
                ->foreign('aerodrome_id')
                ->references('id')
                ->on('navigation_aerodromes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('chart_id')
                ->references('id')
                ->on('navigation_charts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('teamspeak_registration', function (Blueprint $table) {
            $table
                ->foreign('account_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('teamspeak_confirmation', function (Blueprint $table) {
            $table
                ->foreign('registration_id')
                ->references('id')
                ->on('teamspeak_registration')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_account_data', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
        });
        Schema::table('membership_account_settings', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
        });
        Schema::table('membership_notes', function (Blueprint $table) {
            $table->dropForeign(['account_id', 'author_id']);
        });
        Schema::table('membership_bans', function (Blueprint $table) {
            $table->dropForeign(['account_id', 'author_id']);
        });

        Schema::table('bookings_atc', function (Blueprint $table) {
            $table->dropForeign(['controller_id', 'station_id']);
            $table->unsignedInteger('station_id')->change();
        });
        Schema::table('regionalgroup_requests', function (Blueprint $table) {
            $table->dropForeign(['regionalgroup_id', 'account_id']);
            $table->unsignedInteger('regionalgroup_id')->change();
        });
        Schema::table('regionalgroup_eventler', function (Blueprint $table) {
            $table->dropForeign(['regionalgroup_id', 'account_id']);
            $table->unsignedInteger('regionalgroup_id')->change();
        });
        Schema::table('regionalgroup_navigators', function (Blueprint $table) {
            $table->dropForeign(['regionalgroup_id', 'account_id']);
            $table->unsignedInteger('regionalgroup_id')->change();
        });
        Schema::table('regionalgroup_mentors', function (Blueprint $table) {
            $table->dropForeign(['regionalgroup_id', 'account_id']);
            $table->unsignedInteger('regionalgroup_id')->change();
        });
        Schema::table('regionalgroups_account_regionalgroup', function (Blueprint $table) {
            $table->dropForeign(['regionalgroup_id', 'account_id']);
            $table->unsignedInteger('regionalgroup_id')->change();
        });
        Schema::table('regionalgroups_regionalgroups', function (Blueprint $table) {
            $table->dropForeign(['chief_id', 'deputy_id']);
        });
        Schema::table('naviagtion_aerodrome_regionalgroup', function (Blueprint $table) {
            $table->dropForeign(['aerodrome_id', 'regionalgroup_id']);
            $table->unsignedInteger('regionalgroup_id')->change();
        });
        Schema::table('navigation_aerodrome_station', function (Blueprint $table) {
            $table->dropForeign(['aerodrome_id', 'station_id']);
            $table->unsignedInteger('station_id')->change();
        });
        Schema::table('navigation_aerodrome_chart', function (Blueprint $table) {
            $table->dropForeign(['aerodrome_id', 'chart_id']);
            $table->unsignedInteger('chart_id')->change();
        });
        Schema::table('teamspeak_confirmation', function (Blueprint $table) {
            $table->dropForeign(['registration_id']);
        });
        Schema::table('teamspeak_registration', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
        });
    }
}
