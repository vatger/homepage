<?php

namespace App\Libraries\TeamSpeak;

use App\Models\Membership\User\User as Account;
use App\Models\TeamSpeak\Registration;
use Carbon\Carbon;
use stdClass;

class TeamSpeakWebQuery
{
    use TeamSpeakWebQueryTrait, ServergroupTrait, ChannelgroupTrait, ChannelTrait, ClientTrait, PrivilegekeyTrait, BanTrait;

    // =======================================================================
    //  Registration handling
    // =======================================================================

    /**
     * registerViaUid
     *
     * @param  Account $account
     * @param  string $registration_ip
     * @param  string $uid
     * @return boolean
     */
    public static function registerViaUid(Account $account, string $registration_ip, string $uid): bool
    {
        $search = self::_clientgetdbidfromuid($uid);
        if ($search === false) {
            return false;
        }

        $clientdbid = $search[0]->cldbid;

        $registration = new Registration();
        $registration->account_id = $account->id;
        $registration->registration_ip = $registration_ip;
        $registration->uid = $uid;
        $registration->dbid = $clientdbid;

        $serverGroupId = self::getServergroupId(config('teamspeak.default_group'));

        $description = $account->username . ' (' . $account->id . ')';
        if (self::_clientdbedit($clientdbid, $description) == false) {
            return false;
        }
        if (self::_servergroupaddclient($clientdbid, $serverGroupId) == false) {
            return false;
        }
        $registration->save();
        return true;
    }

    /**
     * removeTSRegistation
     *
     * @param  Registration $registration
     * @return boolean
     */
    public static function removeRegistation(Registration $registration): bool
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
     *
     * @param $client
     * @return void
     */
    public static function checkClient($client): void
    {
        $servergroupId = self::getServergroupId(config('teamspeak.default_group'));
        $registration = Registration::where('uid', $client->client_unique_identifier)
            ->where('dbid', $client->cldbid)
            ->first();
        if ($registration == null) {
            // client has no Registration
            self::_servergroupdelclient($client->cldbid, $servergroupId);
            return;
        }
        self::checkRegistration($registration, $client);
    }

    /**
     * checkRegistration
     *
     * @param  Registration $registration
     * @param  stdClass $client
     * @return void
     */
    private static function checkRegistration(Registration $registration, $client)
    {
        $registration->last_login = Carbon::createFromTimestamp($client->client_lastconnected);
        $registration->last_ip = $client->client_lastip;
        $registration->save();
        $account = $registration->account;
        if ($account == null) {
            return;
        }
        $description = $account->username . ' (' . $account->id . ')';
        if (strcmp($client->client_description, $description) != 0) {
            self::_clientdbedit($client->cldbid, $description);
        }
        self::checkAccount($account);
    }

    /**
     * checkAccount
     *
     * @param  Account $account
     * @return void
     */
    private static function checkAccount(Account $account)
    {
        $has_active_ban = $account->isCurrentlyBanned;

        $registrations = Registration::where('account_id', $account->id)->get();
        foreach ($registrations as $registration) {
            $existingTSBans = self::getBansFromRegistration($registration);
            if ($has_active_ban == true && empty($existingTSBans)) {
                $ban = $account->currentBan;
                self::_banadd($registration->uid, Carbon::now()->diffInSeconds($ban->banned_till), '[Account ' . $account->id . ']' . $ban->reason);
            }

            if ($has_active_ban == false && !empty($existingTSBans)) {
                foreach ($existingTSBans as $ban) {
                    self::_bandel($ban->banid);
                }
            }
        }
    }

    /**
     * getBansFromRegistration
     *
     * @param  Registration $registration
     * @return mixed
     */
    private static function getBansFromRegistration(Registration $registration)
    {
        $allbans = self::_banlist();
        $registrationbans = [];
        if ($allbans == false) {
            return $registrationbans;
        }

        foreach ($allbans as $ban) {
            if (strcmp($ban->uid, $registration->uid) == 0) {
                $registrationbans[] = $ban;
            }
        }
        return $registrationbans;
    }
}
