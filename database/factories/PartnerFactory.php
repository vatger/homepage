<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'logo_url' => $this->faker->imageUrl(320, 160, 'business'),
            'link_url' => $this->faker->url(),
            'description_de' => $this->faker->paragraph(),
            'description_en' => $this->faker->paragraph(),
        ];
    }
}
