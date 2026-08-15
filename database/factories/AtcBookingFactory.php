<?php

namespace Database\Factories;

use App\Models\AtcBooking;
use App\Models\Membership\User;
use App\Models\Navigation\Station;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AtcBookingFactory extends Factory
{
    protected $model = AtcBooking::class;

    public function definition(): array
    {
        $startsAt = Carbon::now()->addHours($this->faker->numberBetween(1, 48))->startOfHour();

        return [
            'controller_id' => fn () => User::factory(),
            'station_id' => fn () => Station::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHours($this->faker->numberBetween(1, 3)),
            'voice' => true,
            'training' => false,
            'exam' => false,
            'event' => false,
        ];
    }

    public function past(): static
    {
        return $this->state(function (): array {
            $startsAt = Carbon::now()->subDays($this->faker->numberBetween(1, 14))->startOfHour();

            return ['starts_at' => $startsAt, 'ends_at' => $startsAt->copy()->addHours(2)];
        });
    }

    public function training(): static
    {
        return $this->state(['training' => true]);
    }

    public function exam(): static
    {
        return $this->state(['exam' => true]);
    }
}
