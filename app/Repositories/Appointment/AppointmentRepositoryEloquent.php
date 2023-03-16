<?php

namespace App\Repositories\Appointment;

use App\DataTransferObjects\Appointment\AppointmentCollection;
use App\DataTransferObjects\Appointment\AppointmentData;
use App\Models\Appointment;
use App\Repositories\Advertiser\AdvertiserRepositoryContract;
use App\Repositories\Consumer\ConsumerRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;

class AppointmentRepositoryEloquent implements AppointmentRepositoryContract
{
    protected $appointment;
    protected $consumerRespository;
    protected $advertiserRepository;

    public function __construct(
        Appointment $appointment,
        ConsumerRepositoryContract $consumerRespository,
        AdvertiserRepositoryContract $advertiserRepository
    ) {
        $this->appointment = $appointment;
        $this->consumerRespository = $consumerRespository;
        $this->advertiserRepository = $advertiserRepository;
    }

    public function insertAppointment(array $appointmentData): AppointmentData
    {
        $consumerUuid = Arr::get($appointmentData, 'consumer_id');

        $consumer = $this->consumerRespository->getConsumerById($consumerUuid);

        $consumerZip = Arr::get($appointmentData, 'consumer_id', $consumer->zip);
        
        $appointment = $this->appointment->create(array_merge([
            'consumer_zip' => $consumerZip,
        ], $appointmentData));

        return new AppointmentData($appointment->toArray());
    }

    public function updateAppointment(string $appointmentUuid, array $appointmentData): AppointmentData
    {
        $appointment = $this->appointment->find($appointmentUuid);

        if (!$appointment) {
            throw new ModelNotFoundException('Appointment not found');
        }

        $appointment->fill($appointmentData)->save();

        return new AppointmentData($appointment->toArray());
    }

    public function updateAppointmentStatus(string $appointmentUuid, string $status): AppointmentData
    {
        $appointment = $this->appointment->find($appointmentUuid);

        if (!$appointment) {
            throw new ModelNotFoundException('Appointment not found');
        }

        $appointment->fill(['status' => $status])->save();

        return new AppointmentData($appointment->toArray());
    }

    public function updateAppointmentStartedAt(string $appointmentUuid, string $datetime): AppointmentData
    {
        $appointment = $this->appointment->find($appointmentUuid);

        if (!$appointment) {
            throw new ModelNotFoundException('Appointment not found');
        }

        $appointment->fill(['started_at' => $datetime])->save();

        return new AppointmentData($appointment->toArray());
    }

    public function updateAppointmentFinishedAt(string $appointmentUuid, string $datetime): AppointmentData
    {
        $appointment = $this->appointment->find($appointmentUuid);

        if (!$appointment) {
            throw new ModelNotFoundException('Appointment not found');
        }

        $appointment->fill(['finished_at' => $datetime])->save();

        return new AppointmentData($appointment->toArray());
    }

    public function getAdvertiserAppointmentsBetween(
        string $advertiserUuid,
        string $date,
        string $startTime,
        string $finishTime
    ): AppointmentCollection {

        $advertiser = $this->advertiserRepository->getAdvertiserById($advertiserUuid);

        $appointments = $this->appointment->query()
            ->where('advertiser_id', $advertiser->id)
            ->where('date', $date)
            ->where('start_time', '<=', $startTime)
            ->where('finish_time', '>=', $finishTime)
            ->where('status', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_PROGRESSING
            ])
            ->get();

        return new AppointmentCollection($appointments->toArray());
    }

    public function getAdvertiserAppointments(
        string $advertiserUuid,
        $date = null
    ): AppointmentCollection {
        $advertiser = $this->advertiserRepository->getAdvertiserById($advertiserUuid);

        $appointments = $this->appointment->query()
            ->where('advertiser_id', $advertiser->id)
            ->when($date, function ($query) use ($date) {
                $query->where('date', $date);
            })->get();

        return new AppointmentCollection($appointments->toArray());
    }
}
