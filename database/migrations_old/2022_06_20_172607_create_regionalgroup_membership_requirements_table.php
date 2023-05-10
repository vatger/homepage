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
        Schema::create('regionalgroup_membership_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('regionalgroup_id');
            $table->json('requirements')->nullable();
            $table->timestamps();

            $table
                ->foreign('regionalgroup_id')
                ->references('id')
                ->on('regionalgroups_regionalgroups')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regionalgroup_membership_requirements');
    }
};
