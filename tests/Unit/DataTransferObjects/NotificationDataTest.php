<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\Notification\NotificationData;
use App\Models\Advertiser;
use App\Models\Notification;
use Tests\TestCase;

class NotificationDataTest extends TestCase
{
    public function test_it_creates_a_formatted_dto_object()
    {
        $notification = Notification::factory()
            ->for(Advertiser::factory())
            ->create();

        $notificationData = new NotificationData($notification->toArray());

        $this->assertObjectHasAttribute('id', $notificationData);
        $this->assertObjectHasAttribute('type', $notificationData);
        $this->assertObjectHasAttribute('message', $notificationData);
        $this->assertObjectHasAttribute('created_at', $notificationData);
        $this->assertObjectHasAttribute('updated_at', $notificationData);

        $this->assertEquals($notification->toArray(), $notificationData->toArray());
    }
}
