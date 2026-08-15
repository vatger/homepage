<?php

namespace Tests\Feature;

use App\Services\LandingTrafficService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_with_a_traffic_snapshot(): void
    {
        $this->mock(LandingTrafficService::class, function ($mock): void {
            $mock->shouldReceive('snapshot')->once()->andReturn([
                'aerodromes' => [],
                'stations' => [],
                'pilots' => [],
                'controllers' => [],
                'summary' => [
                    'departures' => 0,
                    'arrivals' => 0,
                    'controllers' => 0,
                    'atis' => 0,
                ],
            ]);
        });

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('VATSIM Germany');
    }
}
