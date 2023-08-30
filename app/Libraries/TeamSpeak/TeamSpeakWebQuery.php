<?php

namespace App\Libraries\TeamSpeak;

use App\Models\Membership\TeamspeakRegistration;
use App\Models\Membership\User\User;
use Carbon\Carbon;

class TeamSpeakWebQuery
{
    use TeamSpeakWebQueryTrait, ServergroupTrait, ChannelgroupTrait, ChannelTrait, ClientTrait, PrivilegekeyTrait, BanTrait;

    // =======================================================================
    //  Registration handling
    // =======================================================================

    public static function registerViaUid(User $User, string $registration_ip, string $uid): bool
    {
        $search = self::_clientgetdbidfromuid($uid);
        if ($search === false) {
            return false;
        }

        $clientdbid = $search[0]->cldbid;

        $registration = new TeamspeakRegistration();
        $registration->User_id = $User->id;
        $registration->registration_ip = $registration_ip;
        $registration->uid = $uid;
        $registration->dbid = $clientdbid;

        $serverGroupId = self::getServergroupId(config('teamspeak.default_group'));

        $description = $User->username . ' (' . $User->id . ')';
        if (!self::_clientdbedit($clientdbid, $description)) {
            return false;
        }
        if (!self::_servergroupaddclient($clientdbid, $serverGroupId)) {
            return false;
        }
        $registration->save();
        return true;
    }

    public static function removeRegistation(TeamspeakRegistration $registration): bool
    {
        $servergroupId = self::getServergroupId(config('teamspeak.default_group'));
        $clientDBid = $registration->dbid;

        if (self::_servergroupdelclient($clientDBid, $servergroupId) == false) {
            return false;
        }

        self::_clientdbedit($clientDBid, '');

        $registration->delete();
        return true;
    }

    /**
     * Check if a connected client is registered properly
     */
    public static function checkClient(object $client): void
    {
        $servergroupId = self::getServergroupId(config('teamspeak.default_group'));
        $registration = TeamspeakRegistration::where('uid', $client->client_unique_identifier)
            ->where('dbid', $client->cldbid)
            ->first();
        if ($registration == null) {
            // client has no Registration
            self::_servergroupdelclient($client->cldbid, $servergroupId);
            return;
        }
        self::checkRegistration($registration, $client);
    }

    private static function checkRegistration(TeamspeakRegistration $registration, object $client): void
    {
        $registration->last_login = Carbon::createFromTimestamp($client->client_lastconnected);
        $registration->last_ip = $client->client_lastip;
        $registration->save();
        $user = $registration->user;
        if ($user == null) {
            return;
        }
        $description = $user->username . ' (' . $user->id . ')';
        if (strcmp($client->client_description, $description) != 0) {
            self::_clientdbedit($client->cldbid, $description);
        }
        self::checkUser($user);
    }

    public static function checkUser(User $user): void
    {
        $registrations = TeamspeakRegistration::where('user_id', $user->id)->get();

        // ban handling
        $has_active_ban = $user->isCurrentlyBanned;
        foreach ($registrations as $registration) {
            $existingTSBans = self::getBansFromRegistration($registration);
            if ($has_active_ban && empty($existingTSBans)) {
                $ban = $user->currentBan;
                self::_banadd($registration->uid, Carbon::now()->diffInSeconds($ban->banned_till), '[User ' . $user->id . ']' . $ban->reason);
            }

            if (!$has_active_ban && !empty($existingTSBans)) {
                foreach ($existingTSBans as $ban) {
                    self::_bandel($ban->banid);
                }
            }
        }

        //group assignment
        $service_role_ids = $user->service_role_ids('ts.servergroup');

        $all_server_groups = self::listServerGroupIds(with_standard_groups: false);
        //so we don't remove the default role

        $del_server_groups = array_diff($all_server_groups, $service_role_ids);
        foreach ($registrations as $registration) {
            foreach ($service_role_ids as $service_role_id) {
                self::addToServergroup($registration, $service_role_id);
            }
            foreach ($del_server_groups as $service_role_id) {
                self::delFromServergroup($registration, $service_role_id);
            }
        }
    }
}
