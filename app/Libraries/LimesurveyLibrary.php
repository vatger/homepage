<?php

namespace App\Libraries;

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
            throw new Exception('LimesurveyLibrary cant get seesion');
        }
    }

    public function __destruct()
    {
        $this->lsJSONRPCClient->release_session_key($this->sessionKey);
    }

    public function send_req(string $endpoint): object|array|false
    {
        $response = $this->lsJSONRPCClient->list_surveys($this->sessionKey, null);

        if (is_array($response)) {
            $data = json_decode(json_encode($response));
        } else {
            $data = json_decode(json_encode(base64_decode($response)));
        }

        return $data;
    }
}
