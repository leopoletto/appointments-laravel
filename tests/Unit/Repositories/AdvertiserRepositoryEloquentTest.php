<?php

namespace Tests\Unit\Repositories;

use App\Models\Advertiser;
use App\Models\Schedule;
use App\Repositories\Advertiser\AdvertiserRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AdvertiserRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(AdvertiserRepositoryEloquent::class);
    }

    public function test_it_returns_all_advertisers()
    {
        $advertisers = $this->repository->getAllAdvertisers();
        $this->assertCount(0, $advertisers);

        Advertiser::factory()->count(10)->create();

        $advertisers = $this->repository->getAllAdvertisers();
        $this->assertCount(10, $advertisers);
    }

    public function test_it_get_an_advertiser_by_id()
    {
        $advertiser = Advertiser::factory()->create();
        $advertiserFromRepo = $this->repository->getAdvertiserById($advertiser->id);

        $this->assertEquals($advertiser->toArray(), $advertiserFromRepo->toArray());
    }

    public function test_it_trows_an_exception_when_getting_by_id_if_advertiser_is_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->getAdvertiserById(Uuid::uuid4());
    }

    public function test_it_returns_true_when_the_advertiser_is_available()
    {
        $advertiser = Advertiser::factory()
            ->has(Schedule::factory()->state([
                'weekday' => now()->dayOfWeek,
                'start_time' => now()->startOfDay()->addHours(9)->format('H:i:s'),
                'finish_time' => now()->startOfDay()->addHours(15)->format('H:i:s'),
            ]))->create();

        $isAvailable = $this->repository->checkAdvertiserAvailability(
            $advertiser->id,
            now()->format('Y-m-d'),
            now()->startOfDay()->addHours(11)->format('Y-m-d H:i:s'),
            now()->startOfDay()->addHours(14)->format('Y-m-d H:i:s')
        );

        $this->assertTrue($isAvailable);
    }

    public function test_it_returns_false_when_the_advertiser_is_not_available()
    {
        $advertiser = Advertiser::factory()
            ->has(Schedule::factory()->state([
                'weekday' => now()->dayOfWeek,
                'start_time' => now()->startOfDay()->addHours(9)->format('H:i:s'),
                'finish_time' => now()->startOfDay()->addHours(15)->format('H:i:s'),
            ]))->create();

        $isAvailable = $this->repository->checkAdvertiserAvailability(
            $advertiser->id,
            now()->format('Y-m-d'),
            now()->startOfDay()->addHours(16)->format('Y-m-d H:i:s'),
            now()->startOfDay()->addHours(17)->format('Y-m-d H:i:s')
        );

        $this->assertFalse($isAvailable);
    }

    public function test_it_trows_an_exception_when_checking_availability_if_advertiser_is_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->checkAdvertiserAvailability(
            Uuid::uuid4(),
            now()->format('Y-m-d'),
            now()->format('Y-m-d H:i:s'),
            now()->addHours(3)->format('Y-m-d H:i:s')
        );
    }
}
