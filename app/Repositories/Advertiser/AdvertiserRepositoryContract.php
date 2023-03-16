<?php

namespace App\Repositories\Advertiser;

use App\DataTransferObjects\Advertiser\AdvertiserCollection;
use App\DataTransferObjects\Advertiser\AdvertiserData;

interface AdvertiserRepositoryContract
{
    public function getAllAdvertisers(): AdvertiserCollection;

    public function getAdvertiserById(string $uuid): AdvertiserData;

    public function checkAdvertiserAvailability(
        string $advertiserUuid,
        string $date,
        string $startTime,
        string $finishTime
    ): bool;
}
