<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Advertiser\AdvertiserService;
use App\Services\Advertiser\AdvertiserServiceContract;
use App\Services\Appointment\AppointmentService;
use App\Services\Appointment\AppointmentServiceContract;
use App\Services\Schedule\ScheduleService;
use App\Services\Schedule\ScheduleServiceContract;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AdvertiserServiceContract::class, AdvertiserService::class);
        $this->app->bind(AppointmentServiceContract::class, AppointmentService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
