<?php

namespace App\Services\Appointment;

use App\DataTransferObjects\Appointment\AppointmentCollection;
use App\DataTransferObjects\Appointment\AppointmentData;

interface AppointmentServiceContract
{
    public function createAppointment(array $appointmentData): AppointmentData;

    public function cancelAppointment(string $appointmentUuid): AppointmentData;

    public function startService(string $appointmentUuid): AppointmentData;

    public function finishService(string $appointmentUuid): AppointmentData;

    public function checkAppointmentOverlap(
        string $advertiserUuid,
        string $date,
        string $startTime,
        string $finishTime
    ): bool;

    public function getAdvertiserAppointments(
        string $advertiserUuid,
        $period = null
    ): AppointmentCollection;
}
