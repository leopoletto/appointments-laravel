<?php 

namespace App\DataTransferObjects\Appointment;

use Spatie\DataTransferObject\DataTransferObject;

class AppointmentData extends DataTransferObject
{
    public $id;
    public $date;
    public $start_time;
    public $finish_time;
    public $duration;
    public $price;
    public $status;
    public $consumer_zip;
    public $consumer_id;
    public $advertiser_id;
    public $started_at;
    public $finished_at;
    public $created_at;
    public $updated_at;
}
