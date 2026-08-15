<?php

namespace Database\Factories;

use App\Models\Navigation\Fir;
use Illuminate\Database\Eloquent\Factories\Factory;

class NavigationFirFactory extends Factory
{
    protected $model = Fir::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->city().' FIR';

        return [
            'slug' => $this->faker->unique()->slug(2),
            'description' => $this->faker->paragraph(),
            'name' => $name,
            'uir' => false,
        ];
    }
}
