<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('user_settings', function (Blueprint $table) {
            $table->json('policies')->default(new Expression('(JSON_ARRAY())'));
        });

        DB::table('user_settings')->update([
            'policies' => DB::raw("JSON_ARRAY(
            JSON_OBJECT('id', 'gdpr', 'date', gdpr_agreed_at),
            JSON_OBJECT('id', 'imprint', 'date', imprint_agreed_at),
            JSON_OBJECT('id', 'satzung', 'date', satzung_agreed_at),
            JSON_OBJECT('id', 'termsofuse', 'date', termsofuse_agreed_at)
            )"),
        ]);

        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('gdpr_agreed_at');
            $table->dropColumn('imprint_agreed_at');
            $table->dropColumn('satzung_agreed_at');
            $table->dropColumn('termsofuse_agreed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('policies');
        });
    }
};
