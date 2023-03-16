<?php

namespace Tests\Unit\DataTransferObjects;

use App\DataTransferObjects\Appointment\AppointmentCollection;
use App\DataTransferObjects\Appointment\AppointmentData;
use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use Tests\TestCase;

class AppointmentCollectionTest extends TestCase
{
    public function test_it_creates_a_collection_of_appointment_data_object()
    {
        $appointments = Appointment::factory()
            ->for(Advertiser::factory())
            ->for(Consumer::factory())
            ->count(10)
            ->create();

        $collection = AppointmentCollection::create($appointments->toArray());

        $this->assertContainsOnlyInstancesOf(AppointmentData::class, $collection);
    }
}
