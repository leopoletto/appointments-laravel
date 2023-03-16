<?php

namespace Tests\Unit\Models;

use App\Models\Advertiser;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function test_it_uses_has_factory_trait()
    {
        $uses = class_uses(Notification::class);

        $this->assertContains(HasFactory::class, $uses);
    }

    public function test_it_uses_uuid_trait()
    {
        $uses = class_uses(Notification::class);

        $this->assertContains(Uuid::class, $uses);
    }

    public function test_it_has_the_correct_fillable_fields()
    {
        $advertiser = new Notification;

        $this->assertEquals(['id', 'type', 'message', 'advertiser_id'], $advertiser->getFillable());
    }

    public function test_it_belongs_to_an_advertiser()
    {
        $notification = Notification::factory()
            ->for(Advertiser::factory())
            ->create();

        $this->assertInstanceOf(BelongsTo::class, $notification->advertiser());
        $this->assertInstanceOf(Advertiser::class, $notification->advertiser);
    }
}
