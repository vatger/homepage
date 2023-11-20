<?php

namespace App\Libraries;

use App\Models\Membership\User\User;
use App\Models\SurveyKey;
use Exception;
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

    private function send_req(string $endpoint, ...$data): object|array|false
    {
        $response = $this->lsJSONRPCClient->$endpoint($this->sessionKey, $data);

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
        return $this->send_req('list_surveys', null);
    }

    /**
     * @param array<User> $users
     * @return array<SurveyKey>
     */
    public function add_participants(int $survey_id, array $users): array
    {
        $data = collect($users)
            ->map(
                fn(User $u) => (object) [
                    'email' => $u->email,
                    'lastname' => $u->lastname,
                    'firstname' => $u->firstname,
                ],
            )
            ->toArray();
        return $this->send_req('add_participants', $survey_id, $data, true);
    }
}
