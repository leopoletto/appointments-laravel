<?php

namespace Tests\Unit\Providers;

use App\Services\Advertiser\AdvertiserService;
use App\Services\Advertiser\AdvertiserServiceContract;
use App\Services\Appointment\AppointmentService;
use App\Services\Appointment\AppointmentServiceContract;
use Tests\TestCase;

class ServiceServiceProviderTest extends TestCase
{
    public function test_it_binds_the_advertiser_service_correctly()
    {
        $service = app(AdvertiserServiceContract::class);

        $this->assertInstanceOf(AdvertiserService::class, $service);
    }

    public function test_it_binds_the_appointment_service_correctly()
    {
        $service = app(AppointmentServiceContract::class);

        $this->assertInstanceOf(AppointmentService::class, $service);
    }
}
