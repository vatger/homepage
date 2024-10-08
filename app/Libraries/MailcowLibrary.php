<?php

namespace App\Libraries;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;

class MailcowLibrary extends BaseLibrary
{
    public static function send(string $method, string $endpoint, array $data = []): \Psr\Http\Message\ResponseInterface|false
    {
        $client = self::constructClient([
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-Key' => config('mailcow.token'),
            ],
        ]);

        $uri = config('mailcow.url') . '/' . $endpoint;

        try {
            return $client->request($method, $uri, ['json' => $data]);
        } catch (GuzzleException $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    public static function create_email(string $email, string $name, string $pwd): bool
    {
        if (str_contains($email, '@')) {
            $email = explode('@', $email)[0];
        }

        $result = self::send('POST', 'add/mailbox', [
            'active' => '1',
            'domain' => 'vatger.de',
            'local_part' => $email,
            'name' => "VATSIM Germany - $name",
            'password' => $pwd,
            'password2' => $pwd,
            'quota' => '128',
            'force_pw_update' => '1',
            'tls_enforce_in' => '1',
            'tls_enforce_out' => '1',
            'tags' => [],
        ]);
        return $result?->getStatusCode() == 200;
    }

    public static function delete_email(string $email): bool
    {
        Log::info("deleting email " . $email);
        return false;
        $result = self::send('POST', 'delete/mailbox', ["$email"]);
        return $result?->getStatusCode() == 200;
    }
}
