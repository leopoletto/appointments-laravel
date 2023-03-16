<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\StoreAppointmentRequest;
use App\Rules\ZipCode;
use Tests\TestCase;

class StoreAppointmentRequestTest extends TestCase
{
    public function test_it_has_the_correct_validation_rules()
    {
        $request = new StoreAppointmentRequest();

        $this->assertEquals([
            'date' => ['required', 'date', 'date_format:Y-m-d', 'after:yesterday'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'finish_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
            'duration' => ['required', 'numeric', 'max:3'],
            'consumer_id' => ['required'],
            'consumer_zip' => ['nullable', app(ZipCode::class)],
        ], $request->rules());
    }
}
