<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionalgroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regionalgroups_firs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('regionalgroups_regionalgroups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('fir_id')->nullable();
            $table->unsignedSmallInteger('vacc_nbr')->default(0);
            $table->string('email')->default('rg@vatsim-germany.org');
            $table->unsignedInteger('chief_id')->nullable();
            $table->unsignedInteger('deputy_id')->nullable();
            $table->timestamps();
        });

        Schema::create('regionalgroups_account_regionalgroup', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('regionalgroup_id');
            $table->boolean('pilot')->default(true);
            $table->boolean('controller')->default(false);
            $table->boolean('guest')->default(false);
            $table->timestamps();
        });

        Schema::create('regionalgroups_mentors', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('regionalgroup_id');
            $table->boolean('chief')->default(false);
            $table->boolean('senior')->default(false);
            $table->timestamps();
        });

        Schema::create('regionalgroups_navigators', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('regionalgroup_id');
            $table->boolean('chief')->default(false);
            $table->boolean('deputy')->default(false);
            $table->timestamps();
        });

        Schema::create('regionalgroups_eventler', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('regionalgroup_id');
            $table->boolean('chief')->default(false);
            $table->boolean('deputy')->default(false);
            $table->timestamps();
        });

        Schema::create('regionalgroups_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('regionalgroup_id');
            $table->unsignedInteger('account_id');
            $table->enum('type', ['member', 'guest']);
            $table->enum('as', ['pilot', 'controller', 'both']);
            $table->text('reason');
            $table->timestamps();
        });
        /*
        Schema::create('regionalgroups_aerodrome_regionalgroup', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('regionalgroup_id');
            $table->unsignedInteger('aerodrome_id');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regionalgroups_aerodrome_regionalgroup');
        Schema::dropIfExists('regionalgroups_requests');
        Schema::dropIfExists('regionalgroups_eventler');
        Schema::dropIfExists('regionalgroups_navigators');
        Schema::dropIfExists('regionalgroups_mentors');
        Schema::dropIfExists('regionalgroups_account_regionalgroup');
        Schema::dropIfExists('regionalgroups_regionalgroups');
        Schema::dropIfExists('regionalgroups_firs');
    }
}
