<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NavLibrary extends BaseGithubLibrary
{
    public static function check_user(User $user): bool
    {
        $roles = $user->service_role_ids(ServiceRoleType::GitHubGroup, cast_to_int: false);

        $roles = collect($roles)->map(fn ($role) => str_replace('vatger-nav.', '', $role))->toArray();

        $orga_member = self::github_is_in_organization($user, 'vatger-nav');

        if (empty($roles) && $orga_member) {
            self::github_remove_from_organization($user, 'vatger-nav');

            return true;
        }
        if (! empty($roles) && ! $orga_member) {
            self::github_add_to_organization($user, 'vatger-nav');

            return true;
        }

        $current_teams = self::github_get_member_teams($user, 'vatger-nav');

        $to_delete = array_diff($current_teams, $roles);
        $to_add = array_diff($roles, $current_teams);

        foreach ($to_add as $team_slug) {
            self::github_team_add_member($user, 'vatger-nav', $team_slug);
        }

        foreach ($to_delete as $team_slug) {
            self::github_team_remove_member($user, 'vatger-nav', $team_slug);
        }

        return true;
    }

    public static function sync_stations(): void
    {
        $repo = 'VATGER-Nav/datahub';
        $branch = 'production';
        $path = 'api/stations.json';

        $stations = self::github_dl_file($repo, $branch, $path);
        if (empty($stations)) {
            return;
        }
        Station::query()->update(['active' => false]);
        foreach ($stations as $s) {
            try {
                if (! isset($s->logon) || ! isset($s->description)) {
                    continue;
                }

                // create the station
                $d = Station::where('ident', 'LIKE', $s->logon)->firstOrNew();
                $d->setAttribute('active', true);
                $d->setAttribute('ident', $s->logon);
                $d->setAttribute('frequency', floatval($s?->frequency ?? 199.998));
                $d->setAttribute('name', $s->description);
                $gcap_class = $s?->gcap_status ?? '0';
                $gcap_class = $gcap_class == '0' || $gcap_class == '1' ? intval($gcap_class) : 2;
                $d->setAttribute('gcap_class', $gcap_class);
                $d->setAttribute('gcap_class_group', strval($s->gcap_status ?? '0'));
                $d->setAttribute('gcap_training_airport', $s->gcap_training_airport ?? false);
                $d->setAttribute('s1_twr', $s->s1_twr ?? false);
                $d->save();

                // attach to aerodromes
                $aerodromes = [];
                $test_icao = Str::substr($s->logon, 0, 4);
                if (Aerodrome::where('icao', 'LIKE', $test_icao)->exists()) {
                    $aerodromes[] = $test_icao;
                }
                if (isset($s->relevant_airports)) {
                    $aerodromes = array_merge($aerodromes, $s->relevant_airports);
                }
                foreach ($d->aerodromes as $aerodrome) {
                    $d->aerodromes()->detach($aerodrome->id);
                }
                foreach ($aerodromes as $a) {
                    $aerodrome = Aerodrome::where('icao', 'LIKE', $a)->first();
                    if (! $aerodrome) {
                        continue;
                    }
                    $d->aerodromes()->attach($aerodrome->id);
                }
            } catch (\Exception $e) {
            }
        }
    }

    public static function sync_stands(): void
    {
        $client = self::constructClient();

        $repo = 'VATGER-Nav/airport-data';
        $branch = 'production';
        $path = 'api';

        $files = self::github_get_file_list($repo, $branch, $path);

        foreach ($files as $file) {
            if (! str_ends_with($file->name, '.csv')) {
                continue;
            }
            $content = $client->get($file->download_url)->getBody()->getContents();
            $filename = Str::lower($file->name);
            $filePath = "navigation/stands/$filename";
            Storage::put($filePath, $content);

        }
    }

    public static function download_airport_data(string $icao): ?object
    {
        $repo = 'VATGER-Nav/airport-data';
        $branch = 'production';
        $path = 'api/airports.json';

        $airports = Cache::remember('airports-data', 60 * 60 * 4, function () use ($repo, $branch, $path) {
            return self::github_dl_file($repo, $branch, $path)?->airports;
        });

        if (empty($airports)) {
            return null;
        }

        foreach ($airports as $airport) {
            if ($airport?->icao && strtolower($airport?->icao) == strtolower($icao)) {
                return $airport;
            }
        }

        return null;
    }
}
