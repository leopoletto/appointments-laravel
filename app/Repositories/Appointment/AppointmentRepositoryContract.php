<?php

namespace App\Repositories\Appointment;

use App\DataTransferObjects\Appointment\AppointmentCollection;
use App\DataTransferObjects\Appointment\AppointmentData;

interface AppointmentRepositoryContract
{
    public function insertAppointment(array $appointmentData): AppointmentData;

    public function updateAppointment(string $appointmentUuid, array $appointmentData): AppointmentData;

    public function updateAppointmentStatus(
        string $appointmentUuid,
        string $status
    ): AppointmentData;

    public function updateAppointmentStartedAt(
        string $appointmentUuid,
        string $datetime
    ): AppointmentData;

    public function updateAppointmentFinishedAt(
        string $appointmentUuid,
        string $datetime
    ): AppointmentData;

    public function getAdvertiserAppointmentsBetween(
        string $advertiserUuid,
        string $date,
        string $startTime,
        string $finishTime
    ): AppointmentCollection;

    public function getAdvertiserAppointments(
        string $advertiserUuid,
        $date = null
    ): AppointmentCollection;
}
