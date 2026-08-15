<?php

namespace Database\Factories;

use App\Models\Navigation\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationFactory extends Factory
{
    protected $model = Station::class;

    public function definition(): array
    {
        return [
            'ident' => strtoupper($this->faker->unique()->lexify('ED??_TWR')),
            'name' => $this->faker->city().' Tower',
            'frequency' => $this->faker->randomFloat(3, 118, 136),
            'gcap_training_airport' => false,
            'gcap_class' => 0,
            's1_twr' => true,
            'active' => true,
            'description' => $this->faker->sentence(),
        ];
    }

    public function atis(): static
    {
        return $this->state(fn () => ['ident' => strtoupper($this->faker->unique()->lexify('ED??_ATIS'))]);
    }
}
