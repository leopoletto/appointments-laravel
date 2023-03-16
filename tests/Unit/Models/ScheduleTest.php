<?php

namespace Tests\Unit\Models;

use App\Models\Advertiser;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    public function test_it_uses_has_factory_trait()
    {
        $uses = class_uses(Schedule::class);

        $this->assertContains(HasFactory::class, $uses);
    }

    public function test_it_uses_uuid_trait()
    {
        $uses = class_uses(Schedule::class);

        $this->assertContains(Uuid::class, $uses);
    }

    public function test_it_has_the_correct_fillable_fields()
    {
        $schedule = new Schedule;

        $this->assertEquals([
            'id',
            'weekday',
            'start_time',
            'end_time',
        ], $schedule->getFillable());
    }

    public function test_it_belongs_to_an_advertiser()
    {
        $schedule = Schedule::factory()
            ->for(Advertiser::factory())
            ->create();

        $this->assertInstanceOf(BelongsTo::class, $schedule->advertiser());
        $this->assertInstanceOf(Advertiser::class, $schedule->advertiser);
    }
}
