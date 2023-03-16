<?php 

namespace App\DataTransferObjects\Advertiser;

use Spatie\DataTransferObject\DataTransferObject;

class AdvertiserData extends DataTransferObject
{
    public $id;
    public $name;
    public $created_at;
    public $updated_at;
}
