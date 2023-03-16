<?php

namespace Tests\Unit\Services;

use App\DataTransferObjects\Advertiser\AdvertiserAvailabilityData;
use App\Models\Advertiser;
use App\Models\Notification;
use App\Models\Schedule;
use App\Services\Advertiser\AdvertiserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AdvertiserServiceTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(AdvertiserService::class);
    }

    public function test_it_returns_all_advertisers()
    {
        $advertisers = $this->service->getAllAdvertisers();
        $this->assertCount(0, $advertisers);

        Advertiser::factory()->count(10)->create();

        $advertisers = $this->service->getAllAdvertisers();
        $this->assertCount(10, $advertisers);
    }

    public function test_it_get_an_advertiser_by_id()
    {
        $advertiser = Advertiser::factory()->create();
        $advertiserFromService = $this->service->getAdvertiserById($advertiser->id);

        $this->assertEquals($advertiser->toArray(), $advertiserFromService->toArray());
    }

    public function test_it_throws_an_exception_if_do_not_found_an_advertiser()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->service->getAdvertiserById(Uuid::uuid4());
    }

    public function test_it_returns_a_correct_advertiser_availability_data_object()
    {
        $advertiser = Advertiser::factory()
            ->has(Schedule::factory()->state([
                'weekday' => now()->addDay()->dayOfWeek,
                'start_time' => now()->addDay()->startOfDay()->addHours(9)->format('H:i:s'),
                'finish_time' => now()->addDay()->startOfDay()->addHours(15)->format('H:i:s'),
            ]))->create();

        $availability = $this->service->getAdvertiserAvailability(
            $advertiser->id,
            now()->addDay()->format('Y-m-d'),
            now()->addDay()->startOfDay()->addHours(11)->format('Y-m-d H:i:s'),
            now()->addDay()->startOfDay()->addHours(14)->format('Y-m-d H:i:s')
        );

        $this->assertInstanceOf(AdvertiserAvailabilityData::class, $availability);
        $this->assertEquals(3, $availability->duration);
    }

    public function test_it_throws_an_exception_when_the_advertiser_is_not_available()
    {
        $advertiser = Advertiser::factory()
            ->has(Schedule::factory()->state([
                'weekday' => now()->dayOfWeek,
                'start_time' => now()->startOfDay()->addHours(9)->format('H:i:s'),
                'finish_time' => now()->startOfDay()->addHours(15)->format('H:i:s'),
            ]))->create();

        $this->expectException(ModelNotFoundException::class);

        $this->service->getAdvertiserAvailability(
            $advertiser->id,
            now()->format('Y-m-d'),
            now()->startOfDay()->addHours(7)->format('Y-m-d H:i:s'),
            now()->startOfDay()->addHours(9)->format('Y-m-d H:i:s')
        );
    }

    /**
     * @dataProvider priceTableProvider
     */
    public function test_it_returns_correctly_the_calculated_price($duration, $expectedPrice)
    {
        $price = $this->service->calculateSchedulePrice($duration);

        $this->assertEquals($expectedPrice, $price);
    }

    public function test_it_gets_all_notifications_for_an_advertiser()
    {
        $advertiser = Advertiser::factory()
            ->has(Notification::factory()->count(10))
            ->create();

        $notifications = $this->service->getAdvertiserNotifications($advertiser->id);

        $this->assertCount(10, $notifications);
    }

    public function priceTableProvider()
    {
        return [
            [1, 150],
            [2, 280],
            [3, 410],
        ];
    }
}
