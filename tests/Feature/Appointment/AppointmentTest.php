<?php

namespace Tests\Feature\Appointment;

use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    public function test_it_gets_all_appointments_for_an_advertiser()
    {
        $advertiser = Advertiser::factory()->create();
        $appointments = Appointment::factory()
            ->state(new Sequence(
                ['date' => now()->format('Y-m-d')],
                ['date' => now()->format('Y-m-d')],
                ['date' => now()->addDay()->format('Y-m-d')]
            ))
            ->for(Consumer::factory())
            ->for($advertiser)
            ->count(3)
            ->create();

        $reponse = $this->get(route('advertisers.appointments.index', [
            'uuid' => $advertiser->id,
            'period' => 'today',
        ]));

        $reponse->assertOk()->assertJsonCount(2);
    }

    public function test_it_creates_an_appointment()
    {
        $consumer = Consumer::factory()->create();
        $advertiser = Advertiser::factory()
            ->has(Schedule::factory()->state([
                'weekday' => Carbon::MONDAY,
                'start_time' => '09:00:00',
                'finish_time' => '18:00:00',
            ]))
            ->create();

        $reponse = $this->post(route('advertisers.appointments.store', [
            'uuid' => $advertiser->id,
            'date' => now()->addWeek()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'),
            'start_time' => '10:00:00',
            'finish_time' => '11:00:00',
            'consumer_zip' => '08532020',
            'consumer_id' => $consumer->id,
        ]));

        $reponse->assertCreated();
    }

    public function test_it_cancels_an_appointment()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->create();

        $this->assertEquals(Appointment::STATUS_PENDING, $appointment->status);

        $this->put(route('appointments.cancel', $appointment->id))
            ->assertJsonFragment([
                'status' => Appointment::STATUS_CANCELED
            ]);
    }

    public function test_it_starts_a_service()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->create();

        $this->assertEquals(Appointment::STATUS_PENDING, $appointment->status);
        $this->assertNull($appointment->started_at);

        $this->put(route('appointments.start', $appointment->id))
            ->assertJsonFragment([
                'status' => Appointment::STATUS_PROGRESSING
            ])->assertJsonMissing([
                'started_at' => null
            ]);
    }

    public function test_it_finishes_a_service()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->create();

        $this->assertEquals(Appointment::STATUS_PENDING, $appointment->status);
        $this->assertNull($appointment->finished_at);

        $this->put(route('appointments.finish', $appointment->id))
            ->assertJsonMissing([
                'finished_at' => null
            ]);
    }
}
