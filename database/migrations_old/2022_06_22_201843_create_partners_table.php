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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('created_by')->nullable();
            $table->string('name');
            $table->string('logo_url')->nullable();
            $table->string('link_url')->nullable();
            $table->longText('description_de')->nullable();
            $table->longText('description_en')->nullable();
            $table->timestamps();

            $table
                ->foreign('created_by')
                ->references('id')
                ->on('membership_accounts')
                ->onUpdate('NO ACTION')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
};
