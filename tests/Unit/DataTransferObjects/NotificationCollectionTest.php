<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\Notification\NotificationCollection;
use App\DataTransferObjects\Notification\NotificationData;
use App\Models\Advertiser;
use App\Models\Notification;
use Tests\TestCase;

class NotificationCollectionTest extends TestCase
{
    public function test_it_creates_a_collection_of_notification_data_object()
    {
        $notifications = Notification::factory()
            ->for(Advertiser::factory())
            ->count(10)->create();

        $collection = NotificationCollection::create($notifications->toArray());

        $this->assertContainsOnlyInstancesOf(NotificationData::class, $collection);
    }
}
