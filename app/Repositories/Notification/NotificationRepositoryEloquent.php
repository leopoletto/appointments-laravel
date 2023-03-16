<?php

namespace App\Repositories\Notification;

use App\DataTransferObjects\Notification\NotificationCollection;
use App\DataTransferObjects\Notification\NotificationData;
use App\Models\Advertiser;
use App\Models\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotificationRepositoryEloquent implements NotificationRepositoryContract
{
    protected $notification;
    protected $advertiser;

    public function __construct(
        Notification $notification,
        Advertiser $advertiser
    ) {
        $this->notification = $notification;
        $this->advertiser = $advertiser;
    }

    public function insertNotification(string $advertiserUuid, string $type, string $message): NotificationData
    {
        $advertiser =  $this->advertiser->find($advertiserUuid);

        if (!$advertiser) {
            throw new ModelNotFoundException('Advertiser not found');
        }
        
        $notification = $this->notification->create([
            'type' => $type,
            'message' => $message,
            'advertiser_id' => $advertiserUuid
        ]);

        return new NotificationData($notification->toArray());
    }

    public function getAdvertiserNotifications(string $advertiserUuid): NotificationCollection
    {
        $advertiser =  $this->advertiser->find($advertiserUuid);

        if (!$advertiser) {
            throw new ModelNotFoundException('Advertiser not found');
        }
        
        return NotificationCollection::create(
            $this->notification->where('advertiser_id', $advertiserUuid)->get()->toArray()
        );
    }
}
