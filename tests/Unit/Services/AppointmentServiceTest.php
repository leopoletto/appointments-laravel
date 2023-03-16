<?php

namespace Tests\Unit\Services;

use App\DataTransferObjects\Appointment\AppointmentData;
use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use App\Services\Appointment\AppointmentService;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class AppointmentServiceTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(AppointmentService::class);
    }

    public function test_it_creates_an_appointment()
    {
        $consumer = Consumer::factory()->create();
        $advertiser = Advertiser::factory()->create();

        $startTime = now()->startOfDay()->addHours(10);
        $endTime = now()->startOfDay()->addHours(13);

        $appointment = $this->service->createAppointment([
            'date' => now()->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'finish_time' => $endTime->format('H:i:s'),
            'duration' => $startTime->diffInHours($endTime),
            'price' => 410,
            'consumer_zip' => $consumer->zip,
            'consumer_id' => $consumer->id,
            'advertiser_id' => $advertiser->id
        ]);

        $this->assertInstanceOf(AppointmentData::class, $appointment);
        $this->assertObjectHasAttribute('id', $appointment);
    }

    public function test_it_cancels_an_appointment()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->create();

        $this->assertEquals(Appointment::STATUS_PENDING, $appointment->status);

        $appointment = $this->service->cancelAppointment($appointment->id);

        $this->assertEquals(Appointment::STATUS_CANCELED, $appointment->status);
    }

    public function test_it_starts_a_service()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->create();

        $this->assertEquals(Appointment::STATUS_PENDING, $appointment->status);
        $this->assertNull($appointment->started_at);

        $appointment = $this->service->startService($appointment->id);

        $this->assertEquals(Appointment::STATUS_PROGRESSING, $appointment->status);
        $this->assertNotNull($appointment->started_at);
    }

    public function test_it_finishes_a_service()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->state([
                'date' => now()->yesterday()->format('Y-m-d'),
                'status' => Appointment::STATUS_PROGRESSING
            ])
            ->create();

        $this->assertEquals(Appointment::STATUS_PROGRESSING, $appointment->status);
        $this->assertNull($appointment->finished_at);

        $appointment = $this->service->finishService($appointment->id);

        $this->assertEquals(Appointment::STATUS_COMPLETED, $appointment->status);
        $this->assertNotNull($appointment->finished_at);
    }

    public function test_it_check_if_there_is_an_appointment_overlap()
    {
        $advertiser = Advertiser::factory()->create();

        Appointment::factory()
            ->state([
                'date' => now()->format('Y-m-d'),
                'start_time' => now()->startOfDay()->addHours(10)->format('H:i:s'),
                'finish_time' => now()->startOfDay()->addHours(11)->format('H:i:s'),
            ])
            ->for(Consumer::factory())
            ->for($advertiser)
            ->create();

        $hasOverlap = $this->service->checkAppointmentOverlap(
            $advertiser->id,
            now()->format('Y-m-d'),
            now()->startOfDay()->addHours(7)->format('H:i:s'),
            now()->startOfDay()->addHours(9)->format('H:i:s'),
        );

        $this->assertFalse($hasOverlap);

        $hasOverlap = $this->service->checkAppointmentOverlap(
            $advertiser->id,
            now()->format('Y-m-d'),
            now()->startOfDay()->addHours(10)->format('H:i:s'),
            now()->startOfDay()->addHours(11)->format('H:i:s'),
        );

        $this->assertTrue($hasOverlap);
    }

    /**
     * @dataProvider periodsProvider
     */
    public function test_it_gets_all_advertiser_appointments($period = null, $expected)
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

        $appointments = $this->service->getAdvertiserAppointments(
            $advertiser->id,
            $period
        );

        $this->assertCount($expected, $appointments);
    }

    public function periodsProvider(): array
    {
        return [
            ['today', 2],
            [null, 3],
        ];
    }
}
