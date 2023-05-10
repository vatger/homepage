<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtdSoloTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tabelle für die Solophasen des ATD
        Schema::create('uts_atd_solophases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('days')->default(30);
            $table->timestamps();
        });

        /*
        Solo Option Tower
        Option 1: Beginn Minor --> TRG --> 90 Tage Solo --> Wechsel auf Major --> TRG --> 90 Tage Solo --> CPT Major
        Option 2: Beginn Minor --> TRG --> 60 Tage Solo --> Wechsel auf Major --> TRG --> 120 Tage Solo --> CPT Major
        Option 3: Beginn Minor --> TRG --> 120 Tage Solo --> Wechsel auf Major --> TRG --> 60 Tage Solo --> CPT Major
        Option 4: Beginn Minor --> TRG --> 180 Tage Solo --> CPT Minor
        Option 5: Beginn Major --> TRG --> 180 Tage Solo --> CPT Major
        Alle anderen sind 30 Tage
        */
        DB::table('uts_atd_solophases')->insert([
            ['title' => 'Ground', 'days' => 30],
            ['title' => 'Tower O1 P1', 'days' => 90],
            ['title' => 'Tower O1 P2', 'days' => 90],
            ['title' => 'Tower O2 P1', 'days' => 60],
            ['title' => 'Tower O2 P2', 'days' => 120],
            ['title' => 'Tower O3 P1', 'days' => 120],
            ['title' => 'Tower O3 P2', 'days' => 60],
            ['title' => 'Tower Minor', 'days' => 180],
            ['title' => 'Tower Major', 'days' => 180],
            ['title' => 'Approach Minor', 'days' => 30],
            ['title' => 'Approach Major', 'days' => 30],
            ['title' => 'Center', 'days' => 30],
        ]);

        // Tabelle der Solofreigaben des ATD
        Schema::create('uts_atd_solo_clearances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('candidate_id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('solophase_id');
            $table->timestamp('begins_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('approved')->default(false);
            $table->unsignedInteger('extensions')->default(0);
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
        Schema::dropIfExists('uts_atd_solo_clearances');
        Schema::dropIfExists('uts_atd_solophases');
    }
}
