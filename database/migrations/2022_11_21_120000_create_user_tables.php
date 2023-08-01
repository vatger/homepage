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
        Schema::create('user_users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table
                ->string('email_backup')
                ->nullable()
                ->unique();
            $table->timestamps();
        });

        Schema::create('user_passwords', function (Blueprint $table) {
            $table
                ->foreignId('user_id')
                ->primary()
                ->constrained('user_users');
            $table->string('password')->nullable(); // Nullable password field.
            $table->rememberToken(); // Session Remember token (To keep login alive)
            $table->text('oauth_access_token')->nullable();
            $table->text('oauth_refresh_token')->nullable();
            $table->unsignedBigInteger('oauth_token_expires')->nullable();
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table
                ->foreignId('user_id')
                ->primary()
                ->constrained('user_users');
            $table->timestamp('gdpr_agreed_at')->nullable();
            $table->timestamp('termsofuse_agreed_at')->nullable();
            $table->enum('language', ['de', 'en']);
            $table->boolean('dark_mode')->default(0);
            $table->enum('color', ['default', 'cyan', 'red', 'green', 'purple', 'slateblue', 'skobleoff', 'yellow'])->default('default');
        });

        Schema::create('user_service_accounts', function (Blueprint $table) {
            $table
                ->foreignId('user_id')
                ->primary()
                ->constrained('user_users');
            $table->unsignedInteger('forum_id')->nullable();
        });

        Schema::create('user_vatger_details', function (Blueprint $table) {
            $table
                ->foreignId('user_id')
                ->primary()
                ->constrained('user_users');
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('active_member_at')->nullable();
            $table->timestamp('vatger_member_at')->nullable();
            $table->timestamp('active_vatger_member_at')->nullable();
            $table->timestamp('warning_inactive_at')->nullable();
            $table->timestamp('inactive_at')->nullable();
            $table->timestamp('warning_delete_at')->nullable();
            $table->timestamp('delete_at')->nullable();
        });

        Schema::create('user_vatsim_details', function (Blueprint $table) {
            $table
                ->foreignId('user_id')
                ->primary()
                ->constrained('user_users');
            $table->timestamps();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('last_rating_change_at')->nullable();
            $table->integer('rating_atc')->default(0); // VATSIM Controller Rating
            $table->integer('rating_pilot')->default(0); // VATSIM Pilot Rating ( BITMASK )
            $table->integer('rating_military')->default(0);
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
        });

        Schema::create('user_bans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_users');
            $table->foreignId('author_id')->constrained('user_users');
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('homepage')->default(true);
            $table->boolean('forum')->default(true);
            $table->boolean('teamspeak')->default(true);
            $table->boolean('other_services')->default(true);
            $table->timestamps();
        });

        Schema::create('user_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_users');
            $table->foreignId('author_id')->constrained('user_users');
            $table->text('note');
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
        Schema::dropIfExists('user_users');
        Schema::dropIfExists('user_passwords');
        Schema::dropIfExists('user_settings');
        Schema::dropIfExists('user_service_accounts');
        Schema::dropIfExists('user_vatger_details');
        Schema::dropIfExists('user_vatsim_details');
        Schema::dropIfExists('user_bans');
        Schema::dropIfExists('user_notes');
    }
};
