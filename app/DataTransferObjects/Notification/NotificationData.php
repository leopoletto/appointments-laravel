<?php 

namespace App\DataTransferObjects\Notification;

use Spatie\DataTransferObject\DataTransferObject;

class NotificationData extends DataTransferObject
{
    public $id;
    public $type;
    public $message;
    public $advertiser_id;
    public $created_at;
    public $updated_at;
}
