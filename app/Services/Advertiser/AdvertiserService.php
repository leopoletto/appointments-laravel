<?php

namespace App\Services\Advertiser;

use App\DataTransferObjects\Advertiser\AdvertiserAvailabilityData;
use App\DataTransferObjects\Advertiser\AdvertiserCollection;
use App\DataTransferObjects\Advertiser\AdvertiserData;
use App\DataTransferObjects\Notification\NotificationCollection;
use App\Repositories\Advertiser\AdvertiserRepositoryContract;
use App\Repositories\Notification\NotificationRepositoryContract;
use App\Services\Appointment\AppointmentServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

class AdvertiserService implements AdvertiserServiceContract
{
    protected $advertiserRepository;
    protected $notificationRepository;
    protected $appointmentService;

    public function __construct(
        AdvertiserRepositoryContract $advertiserRepository,
        NotificationRepositoryContract $notificationRepository,
        AppointmentServiceContract $appointmentService
    ) {
        $this->advertiserRepository = $advertiserRepository;
        $this->notificationRepository = $notificationRepository;
        $this->appointmentService = $appointmentService;
    }

    public function getAllAdvertisers(): AdvertiserCollection
    {
        return $this->advertiserRepository->getAllAdvertisers();
    }

    public function getAdvertiserById(string $uuid): AdvertiserData
    {
        return $this->advertiserRepository->getAdvertiserById($uuid);
    }

    public function getAdvertiserAvailability(
        string $advertiserUuid,
        string $date,
        string $startTime,
        string $finishTime
    ): AdvertiserAvailabilityData {
        $isAvailable =  $this->advertiserRepository
            ->checkAdvertiserAvailability($advertiserUuid, $date, $startTime, $finishTime);

        $hasOverlap = $this->appointmentService
            ->checkAppointmentOverlap($advertiserUuid, $date, $startTime, $finishTime);

        if (!$isAvailable || $hasOverlap) {
            throw new ModelNotFoundException('Advertiser not available');
        }

        $startTime = Carbon::parse($startTime);
        $finishTime = Carbon::parse($finishTime);

        $duration = $startTime->diffInHours($finishTime);

        return new AdvertiserAvailabilityData([
            'duration' => $duration,
            'price' => $this->calculateSchedulePrice($duration),
            'startTime' => $startTime->format('Y-m-d H:i:s'),
            'finishTime' => $finishTime->format('Y-m-d H:i:s'),
        ]);
    }

    public function calculateSchedulePrice(int $durationInHours): float
    {
        $hourPrice = 150;
        $discountTable = [
            1 => 0,
            2 => 20,
            3 => 40
        ];

        $discount = $discountTable[$durationInHours] ?? 0;

        return ($durationInHours * $hourPrice) - $discount;
    }

    public function getAdvertiserNotifications(string $advertiserUuid): NotificationCollection
    {
        return $this->notificationRepository->getAdvertiserNotifications($advertiserUuid);
    }
}
