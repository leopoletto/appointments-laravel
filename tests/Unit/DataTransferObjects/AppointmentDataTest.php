<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\Appointment\AppointmentData;
use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use Tests\TestCase;

class AppointmentDataTest extends TestCase
{
    public function test_it_creates_a_formatted_dto_object()
    {
        $appointment = Appointment::factory()
            ->for(Advertiser::factory())
            ->for(Consumer::factory())
            ->create();

        $appointmentData = new AppointmentData($appointment->toArray());

        $this->assertObjectHasAttribute('id', $appointmentData);
        $this->assertObjectHasAttribute('date', $appointmentData);
        $this->assertObjectHasAttribute('start_time', $appointmentData);
        $this->assertObjectHasAttribute('finish_time', $appointmentData);
        $this->assertObjectHasAttribute('duration', $appointmentData);
        $this->assertObjectHasAttribute('price', $appointmentData);
        $this->assertObjectHasAttribute('status', $appointmentData);
        $this->assertObjectHasAttribute('consumer_zip', $appointmentData);
        $this->assertObjectHasAttribute('consumer_id', $appointmentData);
        $this->assertObjectHasAttribute('advertiser_id', $appointmentData);
        $this->assertObjectHasAttribute('started_at', $appointmentData);
        $this->assertObjectHasAttribute('finished_at', $appointmentData);
        $this->assertObjectHasAttribute('created_at', $appointmentData);
        $this->assertObjectHasAttribute('updated_at', $appointmentData);

        $this->assertEquals(
            collect($appointment->toArray())->except('started_at', 'finished_at'),
            collect($appointmentData->toArray())->except('started_at', 'finished_at')
        );
    }
}
