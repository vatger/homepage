<?php

namespace Tests\Unit;

use App\Services\ExternalServiceHealthService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExternalServiceHealthServiceTest extends TestCase
{
    public function test_configured_http_services_are_checked_without_exposing_credentials(): void
    {
        config([
            'forum.url' => 'https://board.test',
            'forum.apikey' => 'test-api-key',
            'teamspeak.host' => null,
            'teamspeak.pass' => null,
            'teamspeak.apikey' => null,
        ]);

        Http::fake([
            '*board.test*' => Http::response([], 200),
        ]);

        $services = collect(app(ExternalServiceHealthService::class)->check())->keyBy('key');

        $this->assertSame('up', $services['forum']['state']);
        $this->assertSame('HTTP 200', $services['forum']['detail']);
        $this->assertStringNotContainsString('test-api-key', json_encode($services));
        $this->assertSame('not_configured', $services['teamspeak']['state']);
    }

    public function test_unavailable_http_services_are_reported_as_down(): void
    {
        config([
            'forum.url' => 'https://board.test',
            'forum.apikey' => 'test-api-key',
        ]);

        Http::fake([
            '*board.test*' => Http::response([], 503),
        ]);

        $services = collect(app(ExternalServiceHealthService::class)->check())->keyBy('key');

        $this->assertSame('down', $services['forum']['state']);
        $this->assertSame('HTTP 503', $services['forum']['detail']);
    }
}
