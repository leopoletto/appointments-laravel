<?php

namespace Tests\Unit\Providers;

use App\Repositories\Advertiser\AdvertiserRepositoryContract;
use App\Repositories\Advertiser\AdvertiserRepositoryEloquent;
use App\Repositories\Appointment\AppointmentRepositoryContract;
use App\Repositories\Appointment\AppointmentRepositoryEloquent;
use App\Repositories\Consumer\ConsumerRepositoryContract;
use App\Repositories\Consumer\ConsumerRepositoryEloquent;
use App\Repositories\Notification\NotificationRepositoryContract;
use App\Repositories\Notification\NotificationRepositoryEloquent;
use Tests\TestCase;

class RepositoryServiceProviderTest extends TestCase
{
    public function test_it_binds_the_advertiser_repository_eloquent_correctly()
    {
        $repository = app(AdvertiserRepositoryContract::class);

        $this->assertInstanceOf(AdvertiserRepositoryEloquent::class, $repository);
    }

    public function test_it_binds_the_consumer_repository_eloquent_correctly()
    {
        $repository = app(ConsumerRepositoryContract::class);

        $this->assertInstanceOf(ConsumerRepositoryEloquent::class, $repository);
    }

    public function test_it_binds_the_appointment_repository_eloquent_correctly()
    {
        $repository = app(AppointmentRepositoryContract::class);

        $this->assertInstanceOf(AppointmentRepositoryEloquent::class, $repository);
    }

    public function test_it_binds_the_notification_repository_eloquent_correctly()
    {
        $repository = app(NotificationRepositoryContract::class);

        $this->assertInstanceOf(NotificationRepositoryEloquent::class, $repository);
    }
}
