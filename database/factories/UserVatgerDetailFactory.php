<?php

namespace Database\Factories;

use App\Models\Membership\User;
use App\Models\Membership\UserVatgerDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserVatgerDetailFactory extends Factory
{
    protected $model = UserVatgerDetail::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory(),
            'last_seen_at' => Carbon::now()->subDays(2),
            'registered_at' => Carbon::now()->subYears(2),
        ];
    }

    public function member(): static
    {
        return $this->state([
            'vatger_member_at' => Carbon::now()->subYear(),
            'active_vatger_member_at' => Carbon::now()->subMonths(6),
            'active_member_at' => Carbon::now()->subMonths(6),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(['inactive_at' => Carbon::now()->subDays(10)]);
    }
}
