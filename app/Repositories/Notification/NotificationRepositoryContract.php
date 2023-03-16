<?php 

namespace App\Repositories\Notification;

use App\DataTransferObjects\Notification\NotificationCollection;
use App\DataTransferObjects\Notification\NotificationData;

interface NotificationRepositoryContract
{
    public function insertNotification(string $advertiserUuid, string $type, string $message): NotificationData;

    public function getAdvertiserNotifications(string $advertiserUuid): NotificationCollection;
}