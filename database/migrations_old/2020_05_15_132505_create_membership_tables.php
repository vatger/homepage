<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_accounts', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary(); // Primary ID of an account (equivalent to the VATSIM-ID)
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique(); // Accounts e-mail address (make sure it is only there once)
            $table->string('password')->nullable(); // Nullable password field. We only need this as a local backup
            $table->string('remember_token', 100)->nullable(); // Session Remember token (To keep login alive)
            $table
                ->string('api_token', 80)
                ->unique()
                ->nullable()
                ->default(null); // Unique API key. Needed to authenticate when accessing membership api

            // VATAUTH OAuth2 REQUIRED FIELDS
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->unsignedBigInteger('token_expires')->nullable();

            $table->boolean('setup_completed')->default(false); // Has the account been setup properly?
            $table->timestamps(); // Timestamps of creation and updates
            $table->softDeletes(); // Prevent complete removal of data when an account get's deleted.
        });

        Schema::create('membership_account_data', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->primary(); // Reference to the account
            // Data acquired directly from VATSIM-CERT
            $table->integer('rating_atc')->default(0); // VATSIM Controller Rating
            $table->integer('rating_pilot')->default(0); // VATSIM Pilot Rating ( BITMASK )
            $table->string('experience')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table
                ->double('time_atc', 9, 3)
                ->unsigned()
                ->default(0.0);
            $table
                ->double('time_pilot', 9, 3)
                ->unsigned()
                ->default(0.0);
            $table->string('country_code')->nullable();
            $table->string('country_name')->nullable();
            $table->string('region_code')->nullable();
            $table->string('region_name')->nullable();
            $table->string('division_code')->nullable();
            $table->string('division_name')->nullable();
            $table->string('subdivision_code')->nullable();
            $table->string('subdivision_name')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('suspended')->default(false);
            // Update timestamps
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('membership_account_settings', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->primary();
            $table->enum('language', ['de', 'en']);
            $table->unsignedInteger('forum_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_accounts');
        Schema::dropIfExists('membership_account_data');
        Schema::dropIfExists('membership_account_settings');
    }
}
