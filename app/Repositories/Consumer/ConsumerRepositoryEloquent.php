<?php

namespace App\Repositories\Consumer;

use App\DataTransferObjects\Consumer\ConsumerData;
use App\Models\Consumer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConsumerRepositoryEloquent implements ConsumerRepositoryContract
{
    protected $consumer;

    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function getConsumerById(string $uuid): ConsumerData
    {
        $consumer =  $this->consumer->find($uuid);

        if (!$consumer) {
            throw new ModelNotFoundException('Consumer not found');
        }

        return new ConsumerData($consumer->toArray());
    }
}
