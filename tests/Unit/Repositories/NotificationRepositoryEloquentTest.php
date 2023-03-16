<?php

namespace Tests\Unit\Repositories;

use App\Models\Advertiser;
use App\Models\Notification;
use App\Repositories\Notification\NotificationRepositoryEloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class NotificationRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(NotificationRepositoryEloquent::class);
    }

    public function test_it_inserts_a_notification()
    {
        $advertiser = Advertiser::factory()->create();

        $this->assertCount(0, $this->repository->getAdvertiserNotifications($advertiser->id));

        $this->repository->insertNotification(
            $advertiser->id,
            'notification-type',
            'my-custom-message'
        );

        $this->assertCount(1, $this->repository->getAdvertiserNotifications($advertiser->id));
    }

    public function test_it_returns_advertiser_notifications()
    {
        $advertiser = Advertiser::factory()
            ->has(Notification::factory()->count(10))
            ->create();

        $notifications = $this->repository->getAdvertiserNotifications($advertiser->id);

        $this->assertCount(10, $notifications);
    }

    public function test_it_trows_an_exception_when_getting_by_id_if_advertiser_is_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->getAdvertiserNotifications(Uuid::uuid4());
    }
}
