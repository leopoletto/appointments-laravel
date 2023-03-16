<?php

namespace Tests\Unit\Repositories;

use App\DataTransferObjects\Appointment\AppointmentData;
use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use App\Repositories\Appointment\AppointmentRepositoryEloquent;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AppointmentRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(AppointmentRepositoryEloquent::class);
    }

    public function test_it_inserts_a_new_appointement()
    {
        $consumer = Consumer::factory()->create();
        $advertiser = Advertiser::factory()->create();

        $startTime = now()->startOfDay()->addHours(10);
        $endTime = now()->startOfDay()->addHours(13);

        $appointment = $this->repository->insertAppointment([
            'date' => now()->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'finish_time' => $endTime->format('H:i:s'),
            'duration' => $startTime->diffInHours($endTime),
            'status' => Appointment::STATUS_PENDING,
            'price' => 410,
            'consumer_zip' => $consumer->zip,
            'consumer_id' => $consumer->id,
            'advertiser_id' => $advertiser->id
        ]);

        $this->assertInstanceOf(AppointmentData::class, $appointment);
        $this->assertObjectHasAttribute('id', $appointment);
    }

    public function test_it_updates_the_appointment_status()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->create();

        $this->assertEquals(Appointment::STATUS_PENDING, $appointment->status);

        $appointment = $this->repository->updateAppointmentStatus(
            $appointment->id,
            Appointment::STATUS_PROGRESSING
        );

        $this->assertInstanceOf(AppointmentData::class, $appointment);
        $this->assertEquals(Appointment::STATUS_PROGRESSING, $appointment->status);
    }

    public function test_it_throws_an_exception_when_updating_status_if_appointment_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->updateAppointmentStatus(Uuid::uuid4(), Appointment::STATUS_PROGRESSING);
    }

    public function test_it_updates_the_appointment_started_at()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->create();

        $this->assertNull($appointment->started_at);

        $startedAt = now()->format('Y-m-d H:i:s');

        $appointment = $this->repository->updateAppointmentStartedAt(
            $appointment->id,
            $startedAt
        );

        $this->assertInstanceOf(AppointmentData::class, $appointment);
        $this->assertEquals($startedAt, $appointment->started_at);
    }

    public function test_it_throws_an_exception_when_updating_started_at_if_appointment_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $startedAt = now()->format('Y-m-d H:i:s');
        $this->repository->updateAppointmentStartedAt(Uuid::uuid4(), $startedAt);
    }

    public function test_it_updates_the_appointment_finished_at()
    {
        $appointment = Appointment::factory()
            ->for(Consumer::factory())
            ->for(Advertiser::factory())
            ->create();

        $this->assertNull($appointment->finished_at);

        $finishedAt = now()->format('Y-m-d H:i:s');

        $appointment = $this->repository->updateAppointmentFinishedAt(
            $appointment->id,
            $finishedAt
        );

        $this->assertInstanceOf(AppointmentData::class, $appointment);
        $this->assertEquals($finishedAt, $appointment->finished_at);
    }

    public function test_it_throws_an_exception_when_updating_finished_at_if_appointment_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $finishedAt = now()->format('Y-m-d H:i:s');
        $this->repository->updateAppointmentFinishedAt(Uuid::uuid4(), $finishedAt);
    }

    public function test_it_gets_a_collection_of_appointments_for_an_advertiser_between_dates()
    {
        $advertiser = Advertiser::factory()->create();
        $appointments = Appointment::factory()
            ->state([
                'date' => now()->format('Y-m-d'),
                'start_time' => now()->startOfDay()->addHours(10)->format('H:i:s'),
                'finish_time' => now()->startOfDay()->addHours(11)->format('H:i:s'),
            ])
            ->for(Consumer::factory())
            ->for($advertiser)
            ->create();

        $appointments = $this->repository->getAdvertiserAppointmentsBetween(
            $advertiser->id,
            now()->format('Y-m-d'),
            now()->startOfDay()->addHours(10)->format('H:i:s'),
            now()->startOfDay()->addHours(11)->format('H:i:s'),
        );

        $this->assertCount(1, $appointments);
    }

    /**
     * @dataProvider periodsProvider
     */
    public function test_it_gets_a_collection_of_appointments_for_an_advertiser($date = null, $expected)
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

        $appointments = $this->repository->getAdvertiserAppointments(
            $advertiser->id,
            $date
        );
       
        $this->assertCount($expected, $appointments);
    }

    public function periodsProvider(): array
    {
        return [
            [now()->format('Y-m-d'), 2],
            [now()->addDay()->format('Y-m-d'), 1],
            [null, 3],
        ];
    }
}
