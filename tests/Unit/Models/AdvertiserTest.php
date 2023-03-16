<?php

namespace Tests\Unit\Models;

use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class AdvertiserTest extends TestCase
{
    public function test_it_uses_has_factory_trait()
    {
        $uses = class_uses(Advertiser::class);

        $this->assertContains(HasFactory::class, $uses);
    }

    public function test_it_uses_uuid_trait()
    {
        $uses = class_uses(Advertiser::class);

        $this->assertContains(Uuid::class, $uses);
    }

    public function test_it_has_the_correct_fillable_fields()
    {
        $advertiser = new Advertiser;

        $this->assertEquals(['id', 'name'], $advertiser->getFillable());
    }

    public function test_it_has_many_schedules()
    {
        $advertiser = Advertiser::factory()
            ->has(Schedule::factory()->sequenceWeekday()->count(7))
            ->create();

        $this->assertInstanceOf(HasMany::class, $advertiser->schedules());
        $this->assertCount(7, $advertiser->schedules);
        $this->assertContainsOnlyInstancesOf(Schedule::class, $advertiser->schedules);
    }

    public function test_it_has_many_appointments()
    {
        $advertiser = Advertiser::factory()
            ->has(Appointment::factory()->for(Consumer::factory())->count(10))
            ->create();

        $this->assertInstanceOf(HasMany::class, $advertiser->appointments());
        $this->assertCount(10, $advertiser->appointments);
        $this->assertContainsOnlyInstancesOf(Appointment::class, $advertiser->appointments);
    }

    public function test_it_has_many_notifications()
    {
        $advertiser = Advertiser::factory()
            ->has(Notification::factory()->count(10))
            ->create();

        $this->assertInstanceOf(HasMany::class, $advertiser->notifications());
        $this->assertCount(10, $advertiser->notifications);
        $this->assertContainsOnlyInstancesOf(Notification::class, $advertiser->notifications);
    }

    public function test_it_has_many_statistics()
    {
        $advertiser = Advertiser::factory()
            ->has(Statistic::factory()->count(10))
            ->create();

        $this->assertInstanceOf(HasMany::class, $advertiser->statistics());
        $this->assertCount(10, $advertiser->statistics);
        $this->assertContainsOnlyInstancesOf(Statistic::class, $advertiser->statistics);
    }
}
