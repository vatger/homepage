<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User;
use App\Models\Membership\UserVatsimDetail;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class XenForoLibrary extends BaseLibrary
{
    /**
     * Send an actual call to the XenForo API.
     */
    private static function send(string $method, string $endpoint, array $data, bool $bypass = true, bool $allow_errors = false): false|ResponseInterface
    {
        $client = self::constructClient([
            'headers' => [
                'Accept' => 'application/json',
                'XF-Api-Key' => config('forum.apikey'),
                'XP-Api-User' => 1,
            ],
        ]);

        if ($bypass) {
            $data['api_bypass_permissions'] = 1;
            $url = config('forum.url').'/api/'.$endpoint.'?api_bypass_permissions=1';
        } else {
            $url = config('forum.url').'/api/'.$endpoint;
        }
        if ($allow_errors) {
            try {
                return $client->request($method, $url, ['form_params' => $data, 'http_errors' => false]);
            } catch (GuzzleException $e) {
                Log::error($e->getMessage());
            }
        } else {
            try {
                return $client->request($method, $url, ['form_params' => $data]);
            } catch (GuzzleException $e) {
                Log::debug($e->getMessage());
            }
        }

        return false;
    }

    public static function updateForumAccount(User $user, bool $resetEmail = false): bool
    {
        $user->loadMissing('settings');

        if ($user->settings?->forum_id == null) {
            return false;
        }

        $dataArray = [];

        /**
         * Update username when cert data was updated.
         *
         * 1. Get current user object from the board
         * 2. Compare the names
         * 3. If mismatched: set new name //todo new name already exists
         */
        $result = self::send('GET', 'users/'.$user->settings->forum_id, []);
        if (! $result) {
            return false;
        }

        $response = json_decode($result->getBody()->getContents());
        $forumUserObject = $response->user;

        if ($forumUserObject && trim(preg_replace('/[0-9]+/', '', $forumUserObject->username)) != $user->username) {
            $dataArray['username'] = $user->username;
        }

        if ($resetEmail) {
            $dataArray['email'] = $user->email;
            $user->email_backup = $user->email;
            $user->save();
        }

        // Fetch back the current email set in the forum and save as backup
        if (isset($forumUserObject->email) && ! $resetEmail) {
            $user->email_backup = $forumUserObject->email;
            $user->save();
        }

        /**
         * Array to store forum groups that must be assigned to the user.
         */
        $secondaryGroups = [];

        // Get all forum groups the user has through assigned groups
        $secondaryGroups = array_merge($secondaryGroups, $user->service_role_ids(ServiceRoleType::ForumGroup, true));

        $secondaryGroups = array_merge($secondaryGroups, self::map_vatsim_ratings($user->vatsimDetails));

        // Assign forum groups based upon vatger status
        if (! empty($secondaryGroups)) {
            $dataArray['secondary_group_ids'] = $secondaryGroups;
        } else {
            $dataArray['secondary_group_ids'][] = config('forum.guestGroup');
        }

        // CHECK IF USER IS VATGER INACTIVE
        if ($user->vatgerDetails->is_inactive) {
            $dataArray['secondary_group_ids'] = [];
            $dataArray['secondary_group_ids'][] = config('forum.inactiveGroup');
        }

        // CHECK IF USER IS BANNED
        if ($user->is_currently_forum_banned) {
            $dataArray['secondary_group_ids'] = [];
            $dataArray['secondary_group_ids'][] = config('forum.bannedGroup');
        }

        $dataArray['custom_title'] = $user->id;
        $result = self::send('POST', 'users/'.$user->settings->forum_id, $dataArray);
        if (! $result) {
            return false;
        }
        $response = json_decode($result->getBody()->getContents());

        static::updateModerators($user);

        return (bool) $response->success;
    }

    public static function updateModerators(User $user): bool
    {
        $forum_user_id = $user->settings?->forum_id;
        $is_moderator = ! empty($user->service_role_ids(ServiceRoleType::ForumModerator));

        if ($is_moderator) {
            $data = json_decode(File::get(storage_path('app/configurations/board_moderator_permissions.json')));
            self::send('POST', "moderators/$forum_user_id", $data);
        } else {
            self::send('DELETE', "moderators/$forum_user_id", []);
        }

        return true;
    }

    /**
     * Send an alert to a forum account
     */
    public static function sendForumAlert(User $user, string $message, ?string $link_url = null, ?string $link_text = null): bool
    {
        $forum_user_id = $user->settings?->forum_id;
        if ($forum_user_id == null) {
            return false;
        }
        $dataArray = [];
        $dataArray['to_user_id'] = $forum_user_id;
        $dataArray['alert'] = $message;
        $dataArray['from_user_id'] = 0; // anonymous
        $dataArray['link_url'] = $link_url ?? '';
        $dataArray['link_title'] = $link_text ?? '';
        $res = self::send('POST', 'alerts/', $dataArray);
        if (! $res) {
            return false;
        }

        return true;
    }

    /**
     * Sends a private message to a user in the forum
     */
    public static function sendAccountNotification(User $user, string $title, string $message): bool
    {
        if ($user->settings->forum_id == null) {
            return false;
        }

        $dataArray['recipient_ids'] = [$user->settings->forum_id];

        $dataArray['title'] = $title;
        $dataArray['message'] = $message;
        $dataArray['open_invite'] = false;

        $result = self::send('POST', 'conversations', $dataArray);
        if ($result && $result->getStatusCode() == 200) {
            return true;
        }

        return false;
    }

    public static function deleteForumAccount(User $user): bool
    {
        $forumId = $user->settings->forum_id;
        if ($forumId == null) {
            return true;
        }
        $vid = $user->id;
        $result = self::send('DELETE', 'users/'.$forumId, ['rename_to' => $vid], allow_errors: true);
        if ($result && ($result->getStatusCode() == 200 || $result->getStatusCode() == 404)) {
            $user->settings->forum_id = null;
            $user->settings->save();

            return true;
        }

        return false;
    }

    /**
     * Get the actual username used for this account on the forums
     */
    public static function getForumUsername(User $user): string|false
    {
        if (empty($user->settings->forum_id)) {
            return false;
        }

        return \Cache::remember('xenforo.username.'.$user->id, 120, function () use ($user) {
            $result = self::send('GET', 'users/'.$user->settings->forum_id, []);
            if (! $result) {
                return false;
            }

            $response = json_decode($result->getBody()->getContents());
            $forumUserObject = $response->user;

            return $forumUserObject->username;
        });
    }

    /**
     * Grab the posts in the news threads
     */
    public static function getPost(int|string $postId)
    {
        $result = self::send('GET', 'posts/'.$postId, []);
        if ($result && $result->getStatusCode() == 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    public static function get_groups(): array
    {
        if (Cache::has('XenforoLibrary.Groups.cache_valid')) {
            return Cache::get('XenforoLibrary.Groups');
        }
        sleep(3);
        $response = self::send('GET', 'usergroups', []);
        sleep(3);
        if (! $response || $response->getStatusCode() != 200) {
            return [];
        }
        $result = json_decode($response->getBody()->getContents());
        $groups = [];
        foreach ($result as $group) {
            $groups[] = (object) ['id' => $group->user_group_id, 'name' => $group->title];
        }
        if (! empty($groups)) {
            Cache::forget('XenforoLibrary.Groups');
            Cache::forever('XenforoLibrary.Groups', $groups);
            Cache::put('XenforoLibrary.Groups.cache_valid', true, 60 * 10);
        }

        return $groups;
    }

    public static function get_group_name(int $id): ?string
    {
        $teams = self::get_groups();
        foreach ($teams as $team) {
            if ($team->id == $id) {
                return $team->name;
            }
        }

        return null;
    }

    public static function find_group(string $name): ?int
    {
        $teams = self::get_groups();
        foreach ($teams as $team) {
            if ($team->name == $name) {
                return $team->id;
            }
        }

        return null;
    }

    private static function map_vatsim_ratings(UserVatsimDetail $detail): array
    {
        $groups = [];
        switch ($detail->rating_atc_short) {
            case 'S1':
                $groups[] = self::find_group('_rating_atc_s1');
                break;
            case 'S2':
                $groups[] = self::find_group('_rating_atc_s2');
                break;
            case 'S3':
                $groups[] = self::find_group('_rating_atc_s3');
                break;
            case 'C1':
            case 'C3':
                $groups[] = self::find_group('_rating_atc_c1');
                break;
            case 'SUP':
            case 'ADM':
                $groups[] = self::find_group('_rating_atc_sup');
                break;
        }

        switch ($detail->rating_pilot_short) {
            case 'PPL':
                $groups[] = self::find_group('_rating_pilot_p1');
                break;
            case 'IR':
                $groups[] = self::find_group('_rating_pilot_p2');
                break;
            case 'CMEL':
                $groups[] = self::find_group('_rating_pilot_p3');
                break;
            case 'ATPL':
            case 'FI':
            case 'FE':
                $groups[] = self::find_group('_rating_pilot_p4');
                break;
        }

        return $groups;
    }
}
