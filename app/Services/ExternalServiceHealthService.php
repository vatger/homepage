<?php

namespace App\Services;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ExternalServiceHealthService
{
    /**
     * Return a non-sensitive, best-effort status for the configured external services.
     *
     * @return array<int, array{key: string, name: string, state: string, detail: string}>
     */
    public function check(): array
    {
        $definitions = $this->definitions();
        $statuses = [];
        $httpChecks = [];

        foreach ($definitions as $key => $definition) {
            if (! $definition['configured']) {
                $statuses[$key] = $this->status($key, $definition['name'], 'not_configured', 'Not configured');

                continue;
            }

            if ($definition['type'] === 'tcp') {
                $statuses[$key] = $this->checkTcp($key, $definition);

                continue;
            }

            $httpChecks[$key] = $definition;
        }

        if ($httpChecks !== []) {
            $responses = Http::pool(function (Pool $pool) use ($httpChecks): array {
                $requests = [];
                foreach ($httpChecks as $key => $definition) {
                    $requests[$key] = $pool
                        ->as($key)
                        ->timeout(3)
                        ->connectTimeout(1)
                        ->withHeaders(['User-Agent' => 'VATGER/health-check'])
                        ->get($definition['url']);
                }

                return $requests;
            });

            foreach ($httpChecks as $key => $definition) {
                $response = $responses[$key] ?? null;
                $statuses[$key] = $this->httpStatus($key, $definition['name'], $response);
            }
        }

        return array_values($statuses);
    }

    /**
     * @return array<string, array{name: string, type: string, configured: bool, url?: string, host?: string, port?: int}>
     */
    private function definitions(): array
    {
        return [
            'teamspeak' => [
                'name' => 'TeamSpeak',
                'type' => 'tcp',
                'configured' => filled(config('teamspeak.host'))
                    && (filled(config('teamspeak.pass')) || filled(config('teamspeak.apikey'))),
                'host' => config('teamspeak.host'),
                'port' => (int) config('teamspeak.query_port'),
            ],
            'forum' => [
                'name' => 'Forum / Board',
                'type' => 'http',
                'configured' => filled(config('forum.url')) && filled(config('forum.apikey')),
                'url' => config('forum.url'),
            ],
            'bookstack' => [
                'name' => 'Knowledge base',
                'type' => 'http',
                'configured' => filled(config('bookstack.host'))
                    && filled(config('bookstack.token_id'))
                    && filled(config('bookstack.token_secret')),
                'url' => config('bookstack.host'),
            ],
            'nextcloud' => [
                'name' => 'Nextcloud / DMS',
                'type' => 'http',
                'configured' => filled(config('nextcloud.url'))
                    && filled(config('nextcloud.username'))
                    && filled(config('nextcloud.password')),
                'url' => config('nextcloud.url'),
            ],
            'vikunja' => [
                'name' => 'Vikunja',
                'type' => 'http',
                'configured' => filled(config('vikunja.url'))
                    && filled(config('vikunja.username'))
                    && filled(config('vikunja.password')),
                'url' => config('vikunja.url'),
            ],
            'osticket' => [
                'name' => 'Support / osTicket',
                'type' => 'http',
                'configured' => filled(config('osticket.url')) && filled(config('osticket.token')),
                'url' => config('osticket.url'),
            ],
            'mailcow' => [
                'name' => 'Mailcow',
                'type' => 'http',
                'configured' => filled(config('mailcow.url')) && filled(config('mailcow.token')),
                'url' => config('mailcow.url'),
            ],
        ];
    }

    /** @param array{name: string, host?: string, port?: int} $definition */
    private function checkTcp(string $key, array $definition): array
    {
        $errno = 0;
        $error = '';
        $socket = @fsockopen($definition['host'], $definition['port'], $errno, $error, 1.0);

        if (is_resource($socket)) {
            fclose($socket);

            return $this->status($key, $definition['name'], 'up', 'TCP connection available');
        }

        return $this->status($key, $definition['name'], 'down', 'TCP connection failed');
    }

    private function httpStatus(string $key, string $name, mixed $response): array
    {
        if (! $response instanceof Response) {
            return $this->status($key, $name, 'down', 'Request failed');
        }

        $code = $response->status();
        $state = $code >= 200 && $code < 400 ? 'up' : 'down';

        return $this->status($key, $name, $state, "HTTP $code");
    }

    private function status(string $key, string $name, string $state, string $detail): array
    {
        return compact('key', 'name', 'state', 'detail');
    }
}
