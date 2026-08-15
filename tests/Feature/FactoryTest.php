<?php

namespace Tests\Feature;

use App\Models\AtcBooking;
use App\Models\Groups\Fir;
use App\Models\Membership\User;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use App\Models\Partner;
use Carbon\Carbon;
use Database\Seeders\DemoDataSeeder;
use Database\Seeders\MembershipFirSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_factory_creates_the_application_user_dependencies(): void
    {
        $user = User::factory()->vatgerMember()->create();

        $this->assertDatabaseHas('user_users', ['id' => $user->id]);
        $this->assertNotNull($user->passwords);
        $this->assertSame('de', $user->settings->language);
        $this->assertNotNull($user->vatgerDetails->vatger_member_at);
        $this->assertNotNull($user->vatsimDetails);
    }

    public function test_navigation_and_booking_factories_create_related_records(): void
    {
        $station = Station::factory()->create();
        $aerodrome = Aerodrome::factory()->create();
        $aerodrome->stations()->attach($station, ['order' => 1]);
        $booking = AtcBooking::factory()->for($station)->create();

        $this->assertSame('DE', $aerodrome->country_short);
        $this->assertTrue($aerodrome->stations->contains($station));
        $this->assertTrue($booking->ends_at->isFuture());
        $this->assertSame($station->id, $booking->station->id);
        $this->assertMatchesRegularExpression('/^\d{3}\.\d{3}$/', $station->fixedFrequency);
    }

    public function test_demo_data_seeder_populates_synthetic_homepage_data(): void
    {
        $this->seed(MembershipFirSeeder::class);
        Station::factory()->count(8)->create();
        Aerodrome::factory()->count(6)->create();

        $this->seed(DemoDataSeeder::class);

        $this->assertSame(6, Partner::count());
        $this->assertSame(12, User::count());
        $this->assertSame(8, Station::count());
        $this->assertSame(6, Aerodrome::count());
        $this->assertSame(18, AtcBooking::count());
        $this->assertSame(3, Fir::count());
        $this->assertSame(12, DB::table('user_firs')->count());
        $this->assertTrue(
            AtcBooking::query()->where('starts_at', '>', Carbon::now())->exists()
        );
    }
}
