<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\Advertiser\AdvertiserCollection;
use App\DataTransferObjects\Advertiser\AdvertiserData;
use App\Models\Advertiser;
use Tests\TestCase;

class AdvertiserCollectionTest extends TestCase
{
    public function test_it_creates_a_collection_of_advertiser_data_object()
    {
        $advertisers = Advertiser::factory()->count(10)->create();

        $collection = AdvertiserCollection::create($advertisers->toArray());

        $this->assertContainsOnlyInstancesOf(AdvertiserData::class, $collection);
    }
}
