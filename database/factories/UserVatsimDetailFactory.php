<?php

namespace Database\Factories;

use App\Models\Membership\User;
use App\Models\Membership\UserVatsimDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserVatsimDetailFactory extends Factory
{
    protected $model = UserVatsimDetail::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory(),
            'registered_at' => now()->subYears(2),
            'rating_atc' => $this->faker->randomElement([-1, 0, 1, 2, 3, 4, 5]),
            'rating_pilot' => $this->faker->randomElement([-1, 0, 1, 3, 7, 15]),
            'rating_military' => 0,
            'time_atc' => $this->faker->randomFloat(3, 0, 500),
            'time_pilot' => $this->faker->randomFloat(3, 0, 1000),
            'country_code' => 'DE',
            'country_name' => 'Germany',
            'region_code' => 'EMEA',
            'region_name' => 'Europe, Middle East and Africa',
            'division_code' => 'EUD',
            'division_name' => 'VATEUD',
            'subdivision_code' => 'GER',
            'subdivision_name' => 'Germany',
        ];
    }
}
