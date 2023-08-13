<?php

namespace App\Libraries\Forum;

use App\Models\Membership\User\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class XenForoLibrary
{
    /**
     * Send an actual call to the XenForo API.
     */
    private static function send(string $method, string $endpoint, array $data): false|ResponseInterface
    {
        $client = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'XF-Api-Key' => config('forum.apikey'),
                'XP-Api-User' => 1,
            ],
            'connect_timeout' => 25,
        ]);
        $data['api_bypass_permissions'] = 1;
        $url = config('forum.url') . '/api/' . $endpoint . '?api_bypass_permissions=1';

        try {
            return $client->request($method, $url, ['form_params' => $data]);
        } catch (GuzzleException $e) {
            Log::debug($e->getMessage());
            return false;
        }
    }

    /**
     * Send a post request to a XenForo Api.
     *
     * @param [type] $endpoint [description]
     * @param [type] $formData [description]
     *
     * @return [type] [description]
     */
    private static function _sendAPIPostCommandd($endpoint, $formData)
    {
        $data['headers'] = [
            'Accept' => 'application/json',
            'XF-Api-Key' => config('forum.apikey'),
            'XF-Api-User' => 1,
        ];
        $data['api_bypass_permissions'] = 1;
        $data['form_params'] = $formData;
        $client = new Client();
        try {
            $response = $client->post(config('forum.url') . '/api/' . $endpoint, $data);

            return $response;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return false;
        }
    }

    /**
     * Send an update request to a XenForo API.
     *
     * @param [type] $endpoint [description]
     * @param [type] $formData [description]
     *
     * @return [type] [description]
     */
    private static function _sendAPIPatchCommandd($endpoint, $formData)
    {
        $data['headers'] = [
            'Accept' => 'application/json',
            'XF-Api-Key' => config('forum.apikey'),
            'XF-Api-User' => 1,
        ];
        $data['api_bypass_permissions'] = 1;
        $data['form_params'] = $formData;
        $client = new Client();
        try {
            $response = $client->put(config('forum.url') . '/api/' . $endpoint, $data);

            return $response;
        } catch (ClientException $e) {
            Log::debug($e->getMessage());
            return false;
        }
    }

    /**
     * Send a delete request to XenForo Api.
     *
     * @param [type] $endpoint [description]
     * @param [type] $formData [description]
     *
     * @return [type] [description]
     */
    private static function _sendAPIDeleteCommandd($endpoint, $formData)
    {
        $data['headers'] = [
            'Accept' => 'application/json',
            'XF-Api-Key' => config('forum.apikey'),
            'XF-Api-User' => 1,
        ];
        $data['api_bypass_permissions'] = 1;
        $data['form_params'] = $formData;
        $client = new Client();
        try {
            $response = $client->delete(config('forum.url') . '/api/' . $endpoint, $data);

            return $response;
        } catch (ClientException $e) {
            Log::debug($e->getMessage());
            return false;
        }
    }

    /**
     * A function to create an Account for the XenForo Application via API call.
     *
     * @param User $user [description]
     * @param string $password [description]
     *
     * @return bool
     */
    public static function createForumAccount(User $user, $password, $try = 0)
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
            $accSetting = $user->settings;
            $accSetting->forum_id = $forumUserObject->user->user_id;
            $accSetting->save();

            return true;
        } else {
            ++$try;
            if ($try <= 99) {
                return self::createForumAccount($user, $password, $try);
            }
        }

        return false;
    }

    /**
     * Update an account on the forum.
     *
     * @param User $user [description]
     *
     * @return boolean
     */
    public static function updateForumAccount(User $user): bool
    {
        $user->loadMissing('settings', 'roles', 'regionalgroups');

        if (null == $user->settings) {
            return false;
        }

        if (null == $user->settings->forum_id) {
            return false;
        }

        $dataArray = [];

        /**
         * Issue 133.
         * Update username when cert data was updated.
         *
         * 1. Get current user object from the board
         * 2. Compare the names
         * 3. If mismatch: set new name
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

        /**
         * Array to store forum groups that must be assigned to the user.
         * @var array
         */
        $secondaryGroups = [];

        // Get all forumgroups the user has through assigned groups
        foreach ($user->roles as $grp) {
            $grp->loadMissing('forumgroups');
            foreach ($grp->forumgroups as $fg) {
                if (!array_key_exists($fg->forum_id, $secondaryGroups)) {
                    $secondaryGroups[] = $fg->forum_id;
                }
            }
        }

        /**
         * Assign forum groups based upon regionalgroup status
         *
         */
        /* TODO
        $fgrps = ForumGroup::all();
        foreach ($user->regionalgroups as $rg) {
            if ($rg->chief_id == $user->id || $rg->deputy_id == $user->id) {
                // Account is staff team
                if ($fgrps->contains($rg->staff_group_id)) {
                    $secondaryGroups[] = $fgrps->firstWhere('id', $rg->staff_group_id)->forum_id;
                }
            }

            if ($user->isMentorOfRegionalgroup($rg)) {
                // Mentoring group
                if ($fgrps->contains($rg->mentor_group_id)) {
                    $secondaryGroups[] = $fgrps->firstWhere('id', $rg->mentor_group_id)->forum_id;
                }
            }

            if ($user->isNavigatorOfRegionalgroup($rg)) {
                // Nav group
                if ($fgrps->contains($rg->navler_group_id)) {
                    $secondaryGroups[] = $fgrps->firstWhere('id', $rg->navler_group_id)->forum_id;
                }
            }

            if ($user->isEventlerOfRegionalgroup($rg)) {
                // Eventteam group
                if ($fgrps->contains($rg->eventler_group_id)) {
                    $secondaryGroups[] = $fgrps->firstWhere('id', $rg->eventler_group_id)->forum_id;
                }
            }

            if ($user->isMemberOfRegionalgroup($rg)) {
                // Fullmemeber, give acces to membership group
                if ($fgrps->contains($rg->member_group_id)) {
                    $secondaryGroups[] = $fgrps->firstWhere('id', $rg->member_group_id)->forum_id;
                }
                // Fullmember, give access to the voting group
                // But only if at least 2 month non stop membership
                if ($rg->pivot->created_at <= Carbon::now()->subMonth(2) && $fgrps->contains($rg->voting_group_id)) {
                    $secondaryGroups[] = $fgrps->firstWhere('id', $rg->voting_group_id)->forum_id;
                }
                // If you are assigend to a regionalgroup you are eligable to vote as a vacc member
                // But only if you are a member for at least 2 month
                if ($rg->pivot->created_at <= Carbon::now()->subMonth(2)) {
                    $secondaryGroups[] = 44;
                } // vACC-Forum voting group id => 44;
            }

            if ($user->isGuestOfRegionalgroup($rg)) {
                if ($fgrps->contains($rg->guest_group_id)) {
                    $secondaryGroups[] = $fgrps->firstWhere('id', $rg->guest_group_id)->forum_id;
                }
            }
        }
        */
        $dataArray['secondary_group_ids'] = [];
        if (!empty($secondaryGroups)) {
            $dataArray['secondary_group_ids'] = $secondaryGroups;
        } else {
            $dataArray['secondary_group_ids'][] = config('forum.guestGroup');
        }

        $result = self::_sendAPIPostCommand('users/' . $user->settings->forum_id, $dataArray);
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
     * Set a given forum account to the "discouraged" and "suspended" group
     *
     * @param User $user
     * @return boolean
     */
    public static function banForumAccount(User $user): bool
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

        $result = self::_sendAPIPostCommand('users/' . $user->settings->forum_id, $dataArray);
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
     *
     * @param User $user
     * @param string $message
     * @param string $link_url
     * @param string $link_text
     * @return boolean
     */
    public static function sendForumAlert(User $user, string $message, string $link_url = '', string $link_text = ''): bool
    {
        $forum_user_id = $user?->serviceAccounts()->get('forum_id');
        if ($forum_user_id == null) {
            return false;
        }
        $dataArray = [];
        $dataArray['to_user_id'] = $forum_user_id;
        $dataArray['alert'] = $message;
        $dataArray['from_user_id'] = 0; //anonymous
        if (!empty($link_url) && !empty($link_text)) {
            $dataArray['link_url'] = $link_url;
            $dataArray['link_title'] = $link_text;
        }
        $res = self::send('POST', 'alerts/', $dataArray);
        if (!$res) {
            return false;
        }
        return true;
    }

    /**
     * Sends a private message to a user in the forum
     * @param  [type] $account [description]
     * @param  [type] $title   [description]
     * @param  [type] $message [description]
     * @return [type]          [description]
     */
    public static function sendAccountNotification($account, $title, $message)
    {
        if (null == $account->setting->forum_id) {
            return false;
        }

        $dataArray['recipient_ids'] = [$account->setting->forum_id];

        $dataArray['title'] = $title;
        $dataArray['message'] = $message;
        $dataArray['open_invite'] = false;

        $result = self::_sendAPIPostCommand('conversations', $dataArray);
        if ($result && 200 == $result->getStatusCode()) {
            return true;
        }
        return false;
    }

    /**
     * Delete an forum account.
     *
     * @param [type] $forumId [description]
     * @param [type] $vid     [description]
     *
     * @return [type] [description]
     */
    public static function deleteForumAccount($forumId, $vid)
    {
        $result = self::_sendAPIDeleteCommand('users/' . $forumId, ['rename_to' => $vid]);
        if ($result && 200 == $result->getStatusCode()) {
            return true;
        }

        return false;
    }

    /**
     * Get the actual username used for this account on the forums
     *
     * @param User $account
     *
     * @return String The actual username
     */
    public static function getForumUsername(User $account)
    {
        $result = self::_sendAPICommand('users/' . $account->settings->forum_id, []);
        if (!$result) {
            return false;
        }

        $response = json_decode($result->getBody()->getContents());
        $forumUserObject = $response->user;
        return $forumUserObject->username;
    }

    /**
     * Grab the threads from the news forum
     * @return [type] [description]
     */
    public static function getNewsThreads()
    {
        $result = self::_sendAPICommand('forums/' . config('forum.newsId') . '/threads', []);
        if ($result && 200 == $result->getStatusCode()) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Grab the posts in the news threads
     * @param  [type] $postId [description]
     * @return [type]           [description]
     */
    public static function getPost($postId)
    {
        $result = self::_sendAPICommand('posts/' . $postId, []);
        if ($result && 200 == $result->getStatusCode()) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }
}
