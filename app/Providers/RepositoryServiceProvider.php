<?php

namespace App\Providers;

use App\Jobs\NotifiyAdvertiserJob;
use App\Repositories\Advertiser\AdvertiserRepositoryContract;
use App\Repositories\Advertiser\AdvertiserRepositoryEloquent;
use App\Repositories\Appointment\AppointmentRepositoryContract;
use App\Repositories\Appointment\AppointmentRepositoryEloquent;
use App\Repositories\Consumer\ConsumerRepositoryContract;
use App\Repositories\Consumer\ConsumerRepositoryEloquent;
use App\Repositories\Notification\NotificationRepositoryContract;
use App\Repositories\Notification\NotificationRepositoryEloquent;
use App\Repositories\Schedule\ScheduleRepositoryContract;
use App\Repositories\Schedule\ScheduleRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AdvertiserRepositoryContract::class, AdvertiserRepositoryEloquent::class);
        $this->app->bind(AppointmentRepositoryContract::class, AppointmentRepositoryEloquent::class);
        $this->app->bind(ConsumerRepositoryContract::class, ConsumerRepositoryEloquent::class);
        $this->app->bind(NotificationRepositoryContract::class, NotificationRepositoryEloquent::class);
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
