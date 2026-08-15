<?php

namespace Database\Factories;

use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Fir;
use Illuminate\Database\Eloquent\Factories\Factory;

class AerodromeFactory extends Factory
{
    protected $model = Aerodrome::class;

    public function definition(): array
    {
        return [
            'icao' => strtoupper($this->faker->unique()->lexify('????')),
            'fir_id' => fn () => Fir::factory(),
            'name' => $this->faker->city().' Airport',
            'description' => $this->faker->paragraph(),
            'iata' => strtoupper($this->faker->unique()->lexify('???')),
            'country_short' => 'DE',
            'country_long' => 'Germany',
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'military' => false,
            'civilian' => true,
            'major' => $this->faker->boolean(25),
            'restricted_minor' => false,
            'active' => true,
            'latitude' => $this->faker->latitude(47, 55),
            'longitude' => $this->faker->longitude(5, 15),
            'elevation' => $this->faker->numberBetween(0, 3000),
        ];
    }
}
