<?php 

namespace App\DataTransferObjects\Consumer;

use Spatie\DataTransferObject\DataTransferObject;

class ConsumerData extends DataTransferObject
{
    public $id;
    public $name;
    public $zip;
    public $created_at;
    public $updated_at;
}
