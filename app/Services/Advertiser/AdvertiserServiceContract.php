<?php 

namespace App\Services\Advertiser;

use App\DataTransferObjects\Advertiser\AdvertiserAvailabilityData;
use App\DataTransferObjects\Advertiser\AdvertiserCollection;
use App\DataTransferObjects\Advertiser\AdvertiserData;
use App\DataTransferObjects\Notification\NotificationCollection;

interface AdvertiserServiceContract
{
    public function getAllAdvertisers(): AdvertiserCollection;

    public function getAdvertiserById(string $uuid): AdvertiserData;

    public function getAdvertiserAvailability(
        string $advertiserUuid,
        string $date,
        string $startTime,
        string $finishTime
    ): AdvertiserAvailabilityData;

    public function calculateSchedulePrice(int $durationInHours): float;

    public function getAdvertiserNotifications(string $advertiserUuid): NotificationCollection;
}
