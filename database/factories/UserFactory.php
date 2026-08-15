<?php

namespace Database\Factories;

use App\Models\Membership\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(10000000, 99999999),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_backup' => null,
        ];
    }

    public function german(): static
    {
        return $this->afterCreating(fn (User $user) => $user->settings()->update(['language' => 'de']));
    }

    public function english(): static
    {
        return $this->afterCreating(fn (User $user) => $user->settings()->update(['language' => 'en']));
    }

    public function vatgerMember(): static
    {
        return $this->afterCreating(function (User $user): void {
            $user->vatgerDetails()->update([
                'vatger_member_at' => Carbon::now()->subYear(),
                'active_vatger_member_at' => Carbon::now()->subMonths(6),
                'active_member_at' => Carbon::now()->subMonths(6),
            ]);
        });
    }

    public function inactive(): static
    {
        return $this->afterCreating(fn (User $user) => $user->vatgerDetails()->update([
            'inactive_at' => Carbon::now()->subDays(10),
        ]));
    }
}
