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
        Schema::create('approval_legs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routelegs_table_id');
            $table->string('link');
        });
    }
};
