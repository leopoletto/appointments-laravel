<?php

namespace Tests\Unit\Models;

use App\Models\Advertiser;
use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class StatisticTest extends TestCase
{
    public function test_it_uses_has_factory_trait()
    {
        $uses = class_uses(Statistic::class);

        $this->assertContains(HasFactory::class, $uses);
    }

    public function test_it_uses_uuid_trait()
    {
        $uses = class_uses(Statistic::class);

        $this->assertContains(Uuid::class, $uses);
    }

    public function test_it_has_the_correct_fillable_fields()
    {
        $advertiser = new Statistic;

        $this->assertEquals([
            'date',
            'earnings',
            'worked_hours',
            'performed_services',
            'advertiser_id',
        ], $advertiser->getFillable());
    }

    public function test_it_belongs_to_an_advertiser()
    {
        $statistic = Statistic::factory()
            ->for(Advertiser::factory())
            ->create();

        $this->assertInstanceOf(BelongsTo::class, $statistic->advertiser());
        $this->assertInstanceOf(Advertiser::class, $statistic->advertiser);
    }
}
