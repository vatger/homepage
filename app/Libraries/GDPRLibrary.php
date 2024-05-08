<?php

namespace App\Libraries;

use App\Models\Membership\User\GdprRemoval;
use Carbon\Carbon;

class GDPRLibrary
{

    public static function call_service(GdprRemoval $gdprRemoval, string $service): bool
    {
        if (!in_array($service, $gdprRemoval->pending_services)) {
            return true;
        }
        self::mark_started($gdprRemoval, $service);
        $result = false;
        try {
            switch ($service) {
                case 'board':
                    $result = XenForoLibrary::deleteForumAccount($gdprRemoval->user);
                    break;
                case 'teamspeak':
                    $result = false;
                    break;

            }
        } catch (\Exception $exception) {
        }

        if ($result) {
            self::mark_complete($gdprRemoval, $service);
        }

        return $result;
    }

    public static function mark_started(GdprRemoval $gdprRemoval, string $service): void
    {
        $original_service_data = collect(json_decode($gdprRemoval->service_data));
        $current_data = $original_service_data->first(fn($service_data) => $service_data->name == $service);
        if (empty($current_data)) {
            $current_data = (object)['name' => $service, 'started_at' => Carbon::now(), 'completed_at' => null];
        }
        $rest_data = $original_service_data->filter(fn($service_data) => $service_data->name != $service);
        $new_data = $rest_data->push($current_data);
        $gdprRemoval->service_data = json_encode($new_data->toArray());
        $gdprRemoval->save();
    }

    public static function mark_complete(GdprRemoval $gdprRemoval, string $service): void
    {
        $original_service_data = collect(json_decode($gdprRemoval->service_data));
        $current_data = $original_service_data->first(fn($service_data) => $service_data->name == $service);
        if ($current_data->completed_at == null) {
            $current_data->completed_at = Carbon::now();
        }
        $rest_data = $original_service_data->filter(fn($service_data) => $service_data->name != $service);
        $new_data = $rest_data->push($current_data);
        $gdprRemoval->service_data = json_encode($new_data->toArray());
        $gdprRemoval->save();
    }

}
