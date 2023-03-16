<?php

namespace App\Services\Appointment;

use App\DataTransferObjects\Appointment\AppointmentCollection;
use App\DataTransferObjects\Appointment\AppointmentData;
use App\Jobs\NotifiyAdvertiserJob;
use App\Models\Appointment;
use App\Repositories\Advertiser\AdvertiserRepositoryContract;
use App\Repositories\Appointment\AppointmentRepositoryContract;
use App\Repositories\Consumer\ConsumerRepositoryContract;
use App\Repositories\Notification\NotificationRepositoryContract;
use App\Services\Advertiser\AdvertiserServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

class AppointmentService implements AppointmentServiceContract
{
    protected $appointmentRepository;
    protected $advertiserRepository;
    protected $notificationRepository;
    protected $consumerRepository;

    public function __construct(
        AppointmentRepositoryContract $appointmentRepository,
        AdvertiserRepositoryContract $advertiserRepository,
        ConsumerRepositoryContract $consumerRepository,
        NotificationRepositoryContract $notificationRepository
    ) {
        $this->appointmentRepository = $appointmentRepository;
        $this->advertiserRepository = $advertiserRepository;
        $this->consumerRepository = $consumerRepository;
        $this->notificationRepository = $notificationRepository;
    }

    public function createAppointment(array $appointmentData): AppointmentData
    {
        return $this->appointmentRepository->insertAppointment(array_merge(
            ['status' => Appointment::STATUS_PENDING],
            $appointmentData
        ));
    }

    public function cancelAppointment(string $appointmentUuid): AppointmentData
    {
        $appointment = $this->appointmentRepository->updateAppointmentStatus(
            $appointmentUuid,
            Appointment::STATUS_CANCELED
        );

        if ($appointment->status === Appointment::STATUS_CANCELED) {
            $advertiser = $this->advertiserRepository->getAdvertiserById($appointment->advertiser_id);
            $consumer = $this->consumerRepository->getConsumerById($appointment->consumer_id);

            NotifiyAdvertiserJob::dispatch(
                $advertiser->id,
                'appointment.canceled',
                sprintf(
                    'Hello %s, the consumer %s canceled the appointment of %s from %s to %s',
                    $advertiser->name,
                    $consumer->name,
                    $appointment->date,
                    $appointment->start_time,
                    $appointment->finish_time,
                )
            );
        }

        return $appointment;
    }

    public function startService(string $appointmentUuid): AppointmentData
    {
        $this->appointmentRepository->updateAppointmentStartedAt(
            $appointmentUuid,
            now()->format('Y-m-d H:i:s')
        );

        return $this->appointmentRepository->updateAppointmentStatus(
            $appointmentUuid,
            Appointment::STATUS_PROGRESSING
        );
    }

    public function finishService(string $appointmentUuid): AppointmentData
    {
        $appointment = $this->appointmentRepository->updateAppointmentFinishedAt(
            $appointmentUuid,
            now()->format('Y-m-d H:i:s')
        );

        if($appointment->status !== Appointment::STATUS_PROGRESSING){
            throw new ModelNotFoundException('You cannot finish an appointing that is not in progress');
        }

        $finishTime = Carbon::parse($appointment->date . ' '. $appointment->finish_time);

        if($finishTime->isFuture()){
            throw new ModelNotFoundException('You cannot finish an appointing before the finishing time');
        }

        $startedAt = Carbon::parse($appointment->started_at);
        $finishedAt = Carbon::parse($appointment->finished_at);

        $minutes = $finishedAt->diffInMinutes($startedAt);
        $duration = ceil($minutes / Carbon::MINUTES_PER_HOUR);

        $diff = $appointment->duration - $duration;
        $price = $diff > 0 ? $appointment->price + ($diff * 100) : $appointment->price;

        return $this->appointmentRepository->updateAppointment($appointmentUuid, [
            'status' => Appointment::STATUS_COMPLETED,
            'duration' => $duration,
            'price' => $price
        ]);
    }

    public function checkAppointmentOverlap(
        string $advertiserUuid,
        string $date,
        string $startTime,
        string $finishTime
    ): bool {
        $appointments = $this->appointmentRepository->getAdvertiserAppointmentsBetween(
            $advertiserUuid,
            $date,
            $startTime,
            $finishTime
        );

        return collect($appointments->items())->isNotEmpty();
    }

    public function getAdvertiserAppointments(
        string $advertiserUuid,
        $period = null
    ): AppointmentCollection {
        $date = $period === 'today' ? now()->format('Y-m-d') : null;

        return $this->appointmentRepository->getAdvertiserAppointments(
            $advertiserUuid,
            $date
        );
    }
}
