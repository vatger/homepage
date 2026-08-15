<?php

namespace Database\Factories;

use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\AerodromeLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class AerodromeLinkFactory extends Factory
{
    protected $model = AerodromeLink::class;

    public function definition(): array
    {
        return [
            'aerodrome_id' => fn () => Aerodrome::factory(),
            'name' => $this->faker->words(2, true),
            'href' => $this->faker->url(),
            'type' => $this->faker->randomElement(['website', 'chart', 'wiki']),
        ];
    }
}
