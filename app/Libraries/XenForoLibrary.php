<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class XenForoLibrary extends BaseLibrary
{
    /**
     * Send an actual call to the XenForo API.
     */
    private static function send(string $method, string $endpoint, array $data, bool $bypass = true): false|ResponseInterface
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
            $url = config('forum.url') . '/api/' . $endpoint . '?api_bypass_permissions=1';
        } else {
            $url = config('forum.url') . '/api/' . $endpoint;
        }

        try {
            return $client->request($method, $url, ['form_params' => $data]);
        } catch (GuzzleException $e) {
            Log::debug($e->getMessage());
            return false;
        }
    }

    /**
     * A function to create an Account for the XenForo Application via API call.
     */
    public static function createForumAccount(User $user, string $password, int $try = 0): bool
    {
        if (null != $user->settings->forum_id) {
            return false;
        }
        // Build the data we need from the existing user object
        $dataArray = [];
        // Set all options we need as defaults
        $dataArray['option'] = [
            'content_show_signature' => true,
            'email_on_conversation' => true,
            'push_on_conversation' => true,
            'receive_admin_email' => true,
            'show_dob_year' => false,
            'show_dob_date' => false,
            'is_discouraged' => false,
        ];
        // Profile specific Account-Data
        $dataArray['profile'] = [
            'location' => '',
            'website' => '',
            'about' => '',
            'signature' => '',
        ];
        $dataArray['visible'] = true;
        $dataArray['activity_visible'] = true;
        $dataArray['timezome'] = 'Europe/Berlin';
        $dataArray['custom_title'] = $user->id;

        // Is nothing else than $user->firstname.' '.$user->lastname
        if (0 == $try) {
            $dataArray['username'] = $user->username;
        } else {
            $dataArray['username'] = $user->username . ' ' . $try;
        }
        $dataArray['email'] = $user->email;
        // Set Default Usergroup
        $dataArray['user_group_id'] = config('forum.defaultGroup'); // Default Registered User Group
        $dataArray['is_staff'] = false;

        $dataArray['password'] = $password;

        $dataArray['custom_fields'] = [
            'vatsimid' => $user->id,
        ];

        $result = self::send('POST', 'users', $dataArray);
        if ($result && 200 == $result->getStatusCode()) {
            $body = $result->getBody()->getContents();
            $forumUserObject = json_decode($body);
            $user->settings->forum_id = $forumUserObject->user->user_id;
            $user->settings->save();
            return true;
        } else {
            ++$try;
            if ($try <= 99) {
                return self::createForumAccount($user, $password, $try);
            }
        }
        return false;
    }

    public static function updateForumAccount(User $user): bool
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
        $result = self::send('GET', 'users/' . $user->settings->forum_id, []);
        if (!$result) {
            return false;
        }

        $response = json_decode($result->getBody()->getContents());
        $forumUserObject = $response->user;

        if ($forumUserObject && trim(preg_replace('/[0-9]+/', '', $forumUserObject->username)) != $user->username) {
            $dataArray['username'] = $user->username;
        }

        if (isset($forumUserObject->email)) {
            $user->email_backup = $forumUserObject->email;
            $user->save();
        }
        /**
         * Array to store forum groups that must be assigned to the user.
         */
        $secondaryGroups = [];

        // Get all forum groups the user has through assigned groups
        $secondaryGroups = array_merge($secondaryGroups, $user->service_role_ids(ServiceRoleType::ForumGroup, true));

        //Assign forum groups based upon vatger status

        // TODO FIR mitglied + VATGER mitglied (+ FIR wahlberechtigt + VATGER wahlberechtigt)

        if (!empty($secondaryGroups)) {
            $dataArray['secondary_group_ids'] = $secondaryGroups;
        } else {
            $dataArray['secondary_group_ids'][] = config('forum.guestGroup');
        }

        // CHECK IF USER IS BANNED
        if ($user->is_currently_forum_banned) {
            $dataArray['secondary_group_ids'] = [];
            $dataArray['secondary_group_ids'][] = config('forum.bannedGroup');
        }

        $dataArray['custom_title'] = $user->id;
        $result = self::send('POST', 'users/' . $user->settings->forum_id, $dataArray);
        if (!$result) {
            return false;
        }
        $response = json_decode($result->getBody()->getContents());

        return (bool) $response->success;
    }

    /** @param User $user
     * @return boolean
     * @deprecated
     * Set a given forum account to the "discouraged" and "suspended" group
     *
     */
    private static function banForumAccount(User $user): bool
    {
        $suspendedGroup = config('forum.suspendedGroup');

        // Build the data we need from the existing user object
        $dataArray = [];
        // Set all options we need as defaults
        $dataArray['option'] = [
            'is_discouraged' => false,
        ];
        $dataArray['secondary_group_ids'] = [];
        $dataArray['secondary_group_ids'][] = $suspendedGroup;

        $result = self::send('POST', 'users/' . $user->settings->forum_id, $dataArray);
        if (!$result) {
            return false;
        }
        $response = json_decode($result->getBody()->getContents());
        if ($response->success) {
            return true;
        }

        return false;
    }

    /**
     * Send an alert to a forum account
     */
    public static function sendForumAlert(User $user, string $message, ?string $link_url = null, ?string $link_text = null): bool
    {
        $forum_user_id = $user->settings->get('forum_id');
        if ($forum_user_id == null) {
            return false;
        }
        $dataArray = [];
        $dataArray['to_user_id'] = $forum_user_id;
        $dataArray['alert'] = $message;
        $dataArray['from_user_id'] = 0; //anonymous
        $dataArray['link_url'] = $link_url ?? '';
        $dataArray['link_title'] = $link_text ?? '';
        $res = self::send('POST', 'alerts/', $dataArray);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * Sends a private message to a user in the forum
     */
    public static function sendAccountNotification(User $user, string $title, string $message): bool
    {
        if (null == $user->settings->forum_id) {
            return false;
        }

        $dataArray['recipient_ids'] = [$user->settings->forum_id];

        $dataArray['title'] = $title;
        $dataArray['message'] = $message;
        $dataArray['open_invite'] = false;

        $result = self::send('POST', 'conversations', $dataArray);
        if ($result && 200 == $result->getStatusCode()) {
            return true;
        }
        return false;
    }

    public static function deleteForumAccount(User $user)
    {
        $forumId = $user->settings->forum_id;
        $vid = $user->id;
        $result = self::send('DELETE', 'users/' . $forumId, ['rename_to' => $vid]);
        if ($result && 200 == $result->getStatusCode()) {
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
        return \Cache::remember('xenforo.username.' . $user->id, 120, function () use ($user) {
            $result = self::send('GET', 'users/' . $user->settings->forum_id, []);
            if (!$result) {
                return false;
            }

            $response = json_decode($result->getBody()->getContents());
            $forumUserObject = $response->user;
            return $forumUserObject->username;
        });
    }

    public static function getGroupName(int $id): ?string
    {
        $array = config('forum.groups');
        return null;
    }

    /**
     * Grab the posts in the news threads
     */
    public static function getPost(int|string $postId)
    {
        $result = self::send('GET', 'posts/' . $postId, []);
        if ($result && 200 == $result->getStatusCode()) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }
}
