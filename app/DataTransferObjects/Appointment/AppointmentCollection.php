<?php

namespace App\DataTransferObjects\Appointment;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class AppointmentCollection extends DataTransferObjectCollection
{
    public static function create(array $data): AppointmentCollection
    {
        return new static(AppointmentData::arrayOf($data));
    }
}
