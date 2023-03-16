<?php 

namespace App\DataTransferObjects\Advertiser;

use Spatie\DataTransferObject\DataTransferObject;

class AdvertiserAvailabilityData extends DataTransferObject
{
    public $duration;
    public $price;
    public $startTime;
    public $finishTime;
}
