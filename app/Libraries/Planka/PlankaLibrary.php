<?php

namespace App\Libraries\Planka;

use App\Libraries\Planka\Concerns\HasProjectConcern;
use App\Libraries\Planka\Concerns\HasUserConcern;
use App\Models\Membership\User\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\{GuzzleException};
use Psr\Http\Message\ResponseInterface;

enum ResponseStatus
{
    case RESPONSE_SUCCESS;
    case RESPONSE_UNAUTHENTICATED;
    case RESPONSE_ERROR;
}

class PlankaLibrary
{
    use HasProjectConcern, HasUserConcern;

    const TIMEOUT = 30;

    private string $base_uri = 'https://planka.vatsim-germany.org';
    private Client $_client;

    /**
     * @return void
     * @throws GuzzleException
     * @throws Exception
     */
    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => config('planka.url'),
            'connect_timeout' => self::TIMEOUT,
            'read_timeout' => self::TIMEOUT,
            'timeout' => self::TIMEOUT,
        ]);

        $this->_generateAccessToken();
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseStatus
     * @throws GuzzleException
     * @throws Exception
     */
    private function _checkResponse(ResponseInterface $response): ResponseStatus
    {
        $response_code = $response->getStatusCode();

        switch ($response_code) {
            case 401:
                $this->_generateAccessToken();
                return ResponseStatus::RESPONSE_UNAUTHENTICATED;
            case 409:
                throw new Exception($response_code . ' - ' . $response->getBody());
        }

        return ResponseStatus::RESPONSE_SUCCESS;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     * @throws GuzzleException
     */
    private function _sendRequest(string $method, string $uri, $params = [], $secondCall = false)
    {
        $res = $this->_client->request($method, $uri, $params);
        if ($this->_checkResponse($res) === ResponseStatus::RESPONSE_UNAUTHENTICATED && !$secondCall) {
            $this->_generateAccessToken();
            return $this->_sendRequest($method, $uri, $params, true);
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    private function _generateAccessToken(): void
    {
        // Generate Access token
        $res = $this->_sendRequest('POST', 'access-tokens', [
            'form_params' => [
                'emailOrUsername' => config('planka.user'),
                'password' => config('planka.password'),
            ],
        ]);

        // Parse and store access token
        $accessToken = $res['item'];

        // Check validity of access token
        if (!$accessToken) {
            throw new Exception('Unable to query access token');
        }

        // Create new client with Authorization header
        $this->_client = new Client([
            'base_uri' => config('planka.url'),
            'connect_timeout' => self::TIMEOUT,
            'read_timeout' => self::TIMEOUT,
            'timeout' => self::TIMEOUT,
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);
    }
}
