<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\Consumer\ConsumerData;
use App\Models\Consumer;
use Tests\TestCase;

class ConsumerDataTest extends TestCase
{
    public function test_it_creates_a_formatted_dto_object()
    {
        $consumer = Consumer::factory()->create();

        $consumerData = new ConsumerData($consumer->toArray());

        $this->assertObjectHasAttribute('id', $consumerData);
        $this->assertObjectHasAttribute('name', $consumerData);
        $this->assertObjectHasAttribute('zip', $consumerData);
        $this->assertObjectHasAttribute('created_at', $consumerData);
        $this->assertObjectHasAttribute('updated_at', $consumerData);

        $this->assertEquals($consumer->toArray(), $consumerData->toArray());
    }
}
