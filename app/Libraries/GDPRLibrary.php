<?php

namespace App\Libraries;

use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Models\Groups\Team;
use App\Models\Membership\GdprRemoval;
use App\Models\Membership\User;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class GDPRLibrary
{

    // use this when calling as not the user itself
    public static function mark_for_deletion(User $user, bool $now = false): void
    {
        // if we are currently in the removal process, don't continue
        if (GdprRemoval::where('user_id', $user->id)->whereNull('completed_at')->exists()) {
            return;
        }

        if ($user->vatgerDetails->delete_at) {
            return;
        }

        {
            $current_user = Auth::user();
            Auth::setUser($user);
            Auth::logout();
            Auth::setUser($current_user);
        }

        if ($now) {
            $user->vatgerDetails->update(['delete_at' => Carbon::now()->subHours(25)]);
            return;
        }
        $user->vatgerDetails->update(['delete_at' => Carbon::now()]);
        $date = Carbon::now()->addHours(24);
        $n = new BasicNotification(
            __('membership_library.deletion_notice.title'),
            __('membership_library.deletion_notice.message', ['date' => $date->format('d.m.Y H:i')]),
            'VATGER Membership System',
            __('membership_library.deletion_notice.link'),
            route('vatsim.authentication.connect.login'),
            valid_till: $date,
            delete_at: $date->addDay(),
        );
        $user->notify($n);
    }

    public static function start_deletion(User $user): void
    {
        if (!GdprRemoval::where('user_id', $user->id)->whereNull('completed_at')->exists()) {
            $gdpr = new GdprRemoval();
            $gdpr->user_id = $user->id;
            $gdpr->started_at = Carbon::now();
            $gdpr->service_data = [];
            $gdpr->save();
        }

        // we can safely kick him from all teams
        foreach (Team::all() as $t) {
            $user->removeRole($t->role);
        }
    }

    public static function work(GdprRemoval $gdprRemoval): void
    {
        $todos = $gdprRemoval->pending_services;
        foreach ($todos as $todo) {
            self::call_service($gdprRemoval, $todo);
        }
        $gdprRemoval->fresh();
        $todos = $gdprRemoval->pending_services;
        if (empty($todos) && $gdprRemoval->completed_at == null) {
            $gdprRemoval->completed_at = Carbon::now();
            $gdprRemoval->save();
        }
    }

    private static function call_service(GdprRemoval $gdprRemoval, string $service): void
    {
        if (!in_array($service, $gdprRemoval->pending_services)) {
            return;
        }
        self::mark_started($gdprRemoval, $service);
        $result = false;
        try {
            $result = match ($service) {
                'board' => XenForoLibrary::deleteForumAccount($gdprRemoval->user),
                'teamspeak' => TeamSpeakWebQuery::deleteUser($gdprRemoval->user),
                default => false,
            };
        } catch (\Exception $exception) {
        }

        if ($result) {
            self::mark_complete($gdprRemoval, $service);
        }
    }

    private static function call_api_service(GdprRemoval $gdprRemoval, string $service): bool
    {
        File::get(storage_path("app/configurations/gdpr-removal-services.json"));

        return false;
    }


    private static function mark_started(GdprRemoval $gdprRemoval, string $service): void
    {
        $original_service_data = collect($gdprRemoval->service_data);
        $current_data = $original_service_data->first(fn($service_data) => $service_data->name == $service);
        if (empty($current_data)) {
            $current_data = (object)['name' => $service, 'started_at' => Carbon::now(), 'completed_at' => null];
        }
        $rest_data = $original_service_data->filter(fn($service_data) => $service_data->name != $service);
        $new_data = $rest_data->push($current_data);
        $gdprRemoval->service_data = $new_data->toArray();
        $gdprRemoval->save();
    }

    private static function mark_complete(GdprRemoval $gdprRemoval, string $service): void
    {
        $original_service_data = collect($gdprRemoval->service_data);
        $current_data = $original_service_data->first(fn($service_data) => $service_data->name == $service);
        if ($current_data->completed_at == null) {
            $current_data->completed_at = Carbon::now();
        }
        $rest_data = $original_service_data->filter(fn($service_data) => $service_data->name != $service);
        $new_data = $rest_data->push($current_data);
        $gdprRemoval->service_data = $new_data->toArray();
        $gdprRemoval->save();
    }

}
