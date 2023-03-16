<?php

namespace App\DataTransferObjects\Advertiser;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class AdvertiserCollection extends DataTransferObjectCollection
{
    public static function create(array $data): AdvertiserCollection
    {
        return new static(AdvertiserData::arrayOf($data));
    }
}
