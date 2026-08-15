<?php

namespace Database\Factories;

use App\Models\Membership\User;
use App\Models\TeamspeakRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamspeakRegistrationFactory extends Factory
{
    protected $model = TeamspeakRegistration::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory(),
            'registration_ip' => $this->faker->ipv4(),
            'last_ip' => $this->faker->ipv4(),
            'last_login' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'last_os' => $this->faker->randomElement(['Windows', 'Linux', 'macOS']),
            'uid' => $this->faker->uuid(),
            'dbid' => $this->faker->numberBetween(1, 65000),
        ];
    }
}
