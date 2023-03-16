<?php 

namespace App\Repositories\Consumer;

use App\DataTransferObjects\Consumer\ConsumerData;

interface ConsumerRepositoryContract
{
    public function getConsumerById(string $uuid): ConsumerData;
}