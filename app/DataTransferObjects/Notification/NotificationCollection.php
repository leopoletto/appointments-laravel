<?php

namespace App\DataTransferObjects\Notification;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class NotificationCollection extends DataTransferObjectCollection
{
    public static function create(array $data): NotificationCollection
    {
        return new static(NotificationData::arrayOf($data));
    }
}
