<?php

namespace App\Libraries;

use App\Models\Membership\User\User;
use App\Models\SurveyKey;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use org\jsonrpcphp\JsonRPCClient;

class LimesurveyLibrary
{
    private string $sessionKey;
    private jsonRPCClient $lsJSONRPCClient;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->lsJSONRPCClient = new jsonRPCClient(config('survey.url'));
        $this->sessionKey = $this->lsJSONRPCClient->get_session_key(config('survey.uname'), config('survey.pass'));
        if (is_array($this->sessionKey)) {
            Log::error(json_encode($this->sessionKey));
            throw new Exception('LimesurveyLibrary cant get session');
        }
    }

    public function __destruct()
    {
        $this->lsJSONRPCClient->release_session_key($this->sessionKey);
    }

    public function send_req(string $endpoint, ...$data): object|array|false
    {
        $response = $this->lsJSONRPCClient->$endpoint($this->sessionKey, ...$data);

        if (is_array($response)) {
            $data = json_decode(json_encode($response));
        } else {
            $data = json_decode(json_encode(base64_decode($response)));
        }

        return $data;
    }

    //[
    //  {
    //      +"sid": 833398,
    //      +"surveyls_title": "Wahl Leiter FIR München",
    //      +"startdate": null,
    //      +"expires": null,
    //      +"active": "N",
    //    }
    //]
    public function list_surveys(): array
    {
        return $this->lsJSONRPCClient->list_surveys($this->sessionKey, null);
    }

    /**
     * @param array<User>|Collection<User> $users
     * @return array<SurveyKey>
     */

    // [
    //    [
    //      "sent" => "N",
    //      "remindersent" => "N",
    //      "remindercount" => 0,
    //      "completed" => "N",
    //      "usesleft" => 1,
    //      "emailstatus" => "OK",
    //      "email" => "hollmann.vatsim@gmail.com",
    //      "lastname" => "Hollmann",
    //      "firstname" => "Paul",
    //      "token" => "LnVRoy8PYSPfknd",
    //      "language" => "",
    //      "tid" => "1",
    //      "participant_id" => null,
    //      "blacklisted" => null,
    //      "validfrom" => null,
    //      "validuntil" => null,
    //      "mpid" => null,
    //    ],
    //  ]
    // oder wenn nicht participants table erstellt
    //  [
    //    "status" => "No survey participants table",
    //  ]

    public function add_participants(int $survey_id, array|Collection $users): array|string
    {
        $users_data = collect($users)
            ->map(
                fn(User $u) => (object) [
                    'email' => $u->email,
                    'lastname' => $u->lastname,
                    'firstname' => $u->firstname,
                ],
            )
            ->toArray();

        $response_data = $this->lsJSONRPCClient->add_participants($this->sessionKey, $survey_id, $users_data, true);

        if (!empty($response_data['status'])) {
            return $response_data['status'];
        }

        $res = [];
        foreach ($response_data as $index => $response_data_elem) {
            $s = new SurveyKey();
            $s->user_id = $users[$index]->id;
            $s->name = $survey_id; // TODO get name here
            $s->token = $response_data_elem['token'];
            $s->url = 'https://survey.vatsim-germany.org/';

            $s->save();
            $res[] = $s;
        }
        return $res;
    }
}
