<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\Advertiser\AdvertiserData;
use App\Models\Advertiser;
use Tests\TestCase;

class AdvertiserDataTest extends TestCase
{
    public function test_it_creates_a_formatted_dto_object()
    {
        $advertiser = Advertiser::factory()->create();

        $advertiserData = new AdvertiserData($advertiser->toArray());

        $this->assertObjectHasAttribute('id', $advertiserData);
        $this->assertObjectHasAttribute('name', $advertiserData);
        $this->assertObjectHasAttribute('created_at', $advertiserData);
        $this->assertObjectHasAttribute('updated_at', $advertiserData);

        $this->assertEquals($advertiser->toArray(), $advertiserData->toArray());
    }
}
