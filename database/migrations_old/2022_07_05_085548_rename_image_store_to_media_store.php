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
        Schema::rename('imagestore', 'mediastore');

        Schema::table('mediastore', function (Blueprint $table) {
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
        Schema::table('mediastore', function (Blueprint $table) {
            $table->renameColumn('user_id', 'account_id');
        });

        Schema::rename('mediastore', 'imagestore');
    }
};
