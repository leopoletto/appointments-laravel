<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\Advertiser\AdvertiserAvailabilityData;
use Tests\TestCase;

class AdvertiserAvailabilityDataTest extends TestCase
{
    public function test_it_creates_a_formatted_dto_object()
    {
        $availabilityData = [
            'duration' => 3,
            'price' => 480,
            'startTime' => now()->endOfHour()->format('Y-d-m H:i:s'),
            'finishTime' => now()->endOfHour()->addHours(3)->format('Y-d-m H:i:s')
        ];

        $advertiserAvailabilityData = new AdvertiserAvailabilityData($availabilityData);

        $this->assertObjectHasAttribute('duration', $advertiserAvailabilityData);
        $this->assertObjectHasAttribute('price', $advertiserAvailabilityData);
        $this->assertObjectHasAttribute('startTime', $advertiserAvailabilityData);
        $this->assertObjectHasAttribute('finishTime', $advertiserAvailabilityData);

        $this->assertEquals($availabilityData, $advertiserAvailabilityData->toArray());
    }
}
