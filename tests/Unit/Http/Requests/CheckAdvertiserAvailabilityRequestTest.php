<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\CheckAdvertiserAvailabilityRequest;
use Tests\TestCase;

class CheckAdvertiserAvailabilityRequestTest extends TestCase
{
    public function test_it_has_the_correct_validation_rules()
    {
        $request = new CheckAdvertiserAvailabilityRequest;

        $this->assertEquals([
            'date' => ['required', 'date', 'date_format:Y-m-d', 'after:yesterday'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'finish_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
            'duration' => ['required', 'numeric', 'lte:3'],
        ], $request->rules());
    }
}
