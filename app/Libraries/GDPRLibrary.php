<?php

namespace App\Libraries;

use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Models\Groups\Team;
use App\Models\Membership\GdprRemoval;
use App\Models\Membership\User;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class GDPRLibrary extends BaseLibrary
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

        $current_user = Auth::user();
        Auth::setUser($user);
        Auth::logout();
        if (! empty($current_user)) {
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
        if ($user->isCurrentlyInRemoval()) {
            return;
        }

        $gdpr = new GdprRemoval;
        $gdpr->user_id = $user->id;
        $gdpr->started_at = Carbon::now();
        $gdpr->service_data = [];
        $gdpr->save();

        // we can safely kick him from all teams
        foreach (Team::all() as $t) {
            $user->removeRole($t->role);
        }
    }

    public static function get_current_removal(User $user): ?GdprRemoval
    {
        return GdprRemoval::where('user_id', $user->id)->whereNull('completed_at')->whereNull('canceled_at')->first();
    }

    public static function is_currently_locked(?User $user): bool
    {
        if (! $user) {
            return false;
        }
        $removal = self::get_current_removal($user);

        return $removal ? $removal->locked : false;
    }

    public static function lock_deletion(User $user): bool
    {
        if (! $user->isCurrentlyInRemoval()) {
            return false;
        }
        $removal = self::get_current_removal($user);
        $removal->locked = true;
        $removal->save();

        return true;
    }

    public static function cancel_deletion(User $user): bool
    {
        if (! $user->isCurrentlyInRemoval()) {
            return false;
        }
        $removal = self::get_current_removal($user);
        $removal->canceled_at = Carbon::now();
        $removal->save();
        MembershipLibrary::seen($user);

        return true;
    }

    public static function work(GdprRemoval $gdprRemoval): void
    {
        if (! $gdprRemoval->running) {
            return;
        }
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
        if (! in_array($service, $gdprRemoval->pending_services)) {
            return;
        }
        self::mark_started($gdprRemoval, $service);
        $result = false;
        try {
            $result = match ($service) {
                'board' => XenForoLibrary::deleteForumAccount($gdprRemoval->user),
                'teamspeak' => TeamSpeakWebQuery::deleteUser($gdprRemoval->user),
                'knowledgebase' => BookstackLibrary::delete_user($gdprRemoval->user),
                'moodle' => MoodleLibrary::deleteUser($gdprRemoval->user_id),
                default => self::call_api_service($gdprRemoval->user_id, $service),
            };
        } catch (\Exception $exception) {
        }

        if ($result) {
            self::mark_complete($gdprRemoval, $service);
        }
    }

    public static function call_api_service(int $user_id, string $service, bool $debug = false): bool
    {
        try {
            $services = json_decode(File::get(storage_path('app/configurations/gdpr-removal-services.json')));
        } catch (\Exception $exception) {
            return false;
        }
        foreach ($services as $api_service) {
            if ($api_service->name != $service || $api_service->deletion_type != 'api_request') {
                continue;
            }
            $api_url = str_replace('$id', $user_id, $api_service->endpoint);
            $expected_code = $api_service->request_expected_response_code;
            $method = $api_service->request_method;
            $token = config($api_service->token_config);
            $headers = ['Authorization' => $token];
            $client = self::constructClient([
                'headers' => $headers,
            ]);
            dump($api_url, $expected_code, $method, $token, $headers, $client);
            try {
                $response = $client->request($method, $api_url, ['http_errors' => false]);
            } catch (GuzzleException $e) {
                Log::error($e->getMessage());

                return false;
            }
            if ($debug) {
                dump($response->getStatusCode());
                dump($response->getBody()->getContents());
            }
            $response_code = $response?->getStatusCode();
            if ($response_code == $expected_code) {
                return true;
            }

            return false;
        }

        return false;
    }

    private static function mark_started(GdprRemoval $gdprRemoval, string $service): void
    {
        $original_service_data = collect($gdprRemoval->service_data);
        $current_data = $original_service_data->first(fn ($service_data) => $service_data->name == $service);
        if (empty($current_data)) {
            $current_data = (object) ['name' => $service, 'started_at' => Carbon::now(), 'completed_at' => null];
        }
        $rest_data = $original_service_data->filter(fn ($service_data) => $service_data->name != $service);
        $new_data = $rest_data->push($current_data);
        $gdprRemoval->service_data = $new_data->toArray();
        $gdprRemoval->save();
    }

    private static function mark_complete(GdprRemoval $gdprRemoval, string $service): void
    {
        $original_service_data = collect($gdprRemoval->service_data);
        $current_data = $original_service_data->first(fn ($service_data) => $service_data->name == $service);
        if ($current_data->completed_at == null) {
            $current_data->completed_at = Carbon::now();
        }
        $rest_data = $original_service_data->filter(fn ($service_data) => $service_data->name != $service);
        $new_data = $rest_data->push($current_data);
        $gdprRemoval->service_data = $new_data->toArray();
        $gdprRemoval->save();
    }
}
