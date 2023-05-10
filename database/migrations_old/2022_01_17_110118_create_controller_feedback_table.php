<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControllerFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controller_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('controller_id')->nullable(); //Bspw. für Event-Feedback (für mehrere Stationen)
            $table->string('station_id')->nullable(); //Bspw. EDDF_1_TWR nicht in Datenbank, daher muss die eingegebene Station gespeichert werden
            $table->string('controller_name')->nullable(); //Bspw. falls der Benutzer nur den Namen und nicht die ID kennt
            $table->unsignedInteger('reporter_id');
            $table->text('feedback');
            $table->dateTime('report_date');
            $table->timestamps();

            $table
                ->foreign('controller_id')
                ->references('id')
                ->on('membership_accounts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('reporter_id')
                ->references('id')
                ->on('membership_accounts')
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
        Schema::dropIfExists('controller_feedback');
    }
}
