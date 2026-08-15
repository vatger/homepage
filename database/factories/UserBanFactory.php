<?php

namespace Database\Factories;

use App\Models\Membership\User;
use App\Models\Membership\UserBan;
use App\Models\Membership\UserBanType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserBanFactory extends Factory
{
    protected $model = UserBan::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory(),
            'author_id' => fn () => User::factory(),
            'type' => UserBanType::vatger_ban,
            'starts_at' => Carbon::now()->subDay(),
            'ends_at' => Carbon::now()->addDays(7),
            'homepage' => true,
            'forum' => true,
            'teamspeak' => true,
            'reason' => $this->faker->sentence(),
        ];
    }

    public function permanent(): static
    {
        return $this->state(['ends_at' => null]);
    }

    public function expired(): static
    {
        return $this->state(['ends_at' => Carbon::now()->subDay()]);
    }

    public function homepageOnly(): static
    {
        return $this->state(['homepage' => true, 'forum' => false, 'teamspeak' => false]);
    }
}
