<?php

namespace Database\Factories;

use App\Models\Membership\User;
use App\Models\Membership\UserSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingFactory extends Factory
{
    protected $model = UserSetting::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory(),
            'language' => $this->faker->randomElement(['de', 'en']),
            'dark_mode' => $this->faker->boolean(),
            'color' => 'default',
            'policies' => '[]',
        ];
    }
}
