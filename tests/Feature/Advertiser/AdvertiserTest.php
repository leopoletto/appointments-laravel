<?php

namespace Tests\Feature\Advertiser;

use App\Models\Advertiser;
use App\Models\Schedule;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AdvertiserTest extends TestCase
{
    public function test_it_lists_all_the_advertisers()
    {
        $advertisers = Advertiser::factory()->count(10)->create();

        $response = $this->get(route('advertisers.index'));

        $response->assertOk()
            ->assertSimilarJson($advertisers->toArray())
            ->assertJsonCount(10);
    }

    public function test_it_shows_an_advertiser_by_its_id()
    {
        $advertiser = Advertiser::factory()->create();

        $response = $this->get(route('advertisers.show', $advertiser->id));

        $response->assertOk()->assertSimilarJson($advertiser->toArray());
    }

    public function test_it_fails_when_an_advertiser_id_does_not_exists()
    {
        $response = $this->get(route('advertisers.show', Uuid::uuid4()));

        $response->assertNotFound()
            ->assertSimilarJson([
                'message' => 'Advertiser not found'
            ]);
    }

    public function test_it_returns_the_advertiser_availability_data()
    {
        $this->travelTo(Carbon::yesterday());

        $advertiser = Advertiser::factory()
            ->has(Schedule::factory()->state([
                'weekday' => now()->dayOfWeek,
                'start_time' => '10:00:00',
                'finish_time' => now()->startOfDay()->addHours(15)->format('H:i:s'),
            ]))->create();

        $response = $this->get(route('advertisers.availability', [
            'uuid' => $advertiser->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->startOfDay()->addHours(10)->format('H:i:s'),
            'finish_time' => now()->startOfDay()->addHours(13)->format('H:i:s')
        ]));

        $response->assertOk()
            ->assertSimilarJson([
                'duration' => 3,
                'price' => 410,
                'startTime' => now()->startOfDay()->addHours(10)->format('Y-m-d H:i:s'),
                'finishTime' => now()->startOfDay()->addHours(13)->format('Y-m-d H:i:s')
            ]);
    }

    public function test_it_fails_when_the_advertiser_is_not_available()
    {
        $this->travelTo(Carbon::yesterday());

        $advertiser = Advertiser::factory()
            ->has(Schedule::factory()->state([
                'weekday' => now()->dayOfWeek,
                'start_time' => now()->startOfDay()->addHours(9)->format('H:i:s'),
                'finish_time' => now()->startOfDay()->addHours(15)->format('H:i:s'),
            ]))->create();

        $response = $this->get(route('advertisers.availability', [
            'uuid' => $advertiser->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => now()->addHours(7)->format('H:i:s'),
            'finish_time' => now()->addHours(10)->format('H:i:s')
        ]));

        $response->assertNotFound()
            ->assertSimilarJson([
                'message' => 'Advertiser not available'
            ]);
    }
}
