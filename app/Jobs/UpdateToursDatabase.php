<?php

namespace App\Jobs;

use App\Models\Membership\UserSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateToursDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            DB::connection('mysql_old')->getDatabaseName();
        } catch (\Exception $e) {
            return;
        }
        UserSetting::whereNotNull('forum_id')
            ->cursor()
            ->each(function ($user_setting) {
                self::DB_old('membership_account_settings')
                    ->where('account_id', $user_setting->user_id)
                    ->update(['forum_id' => $user_setting->forum_id]);
            });
    }

    private static function DB_old(string $table = null): Connection|Builder
    {
        if ($table) {
            return DB::connection('mysql_old')->table($table);
        }
        return DB::connection('mysql_old');
    }
}
