<?php

namespace Tests\Unit\Models;

use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    public function test_it_uses_has_factory_trait()
    {
        $uses = class_uses(Appointment::class);

        $this->assertContains(HasFactory::class, $uses);
    }

    public function test_it_uses_uuid_trait()
    {
        $uses = class_uses(Appointment::class);

        $this->assertContains(Uuid::class, $uses);
    }

    public function test_it_has_the_correct_fillable_fields()
    {
        $appointment = new Appointment;

        $this->assertEquals([
            'date',
            'start_time',
            'finish_time',
            'duration',
            'price',
            'status',
            'consumer_zip',
            'consumer_id',
            'advertiser_id',
            'started_at',
            'finished_at',
        ], $appointment->getFillable());
    }

    public function test_it_belongs_to_an_advertiser()
    {
        $appointment = Appointment::factory()
            ->for(Advertiser::factory())
            ->for(Consumer::factory())
            ->create();

        $this->assertInstanceOf(BelongsTo::class, $appointment->advertiser());
        $this->assertInstanceOf(Advertiser::class, $appointment->advertiser);
    }
    
    public function test_it_belongs_to_a_consumer()
    {
        $appointment = Appointment::factory()
            ->for(Advertiser::factory())
            ->for(Consumer::factory())
            ->create();

        $this->assertInstanceOf(BelongsTo::class, $appointment->consumer());
        $this->assertInstanceOf(Consumer::class, $appointment->consumer);
    }
}
