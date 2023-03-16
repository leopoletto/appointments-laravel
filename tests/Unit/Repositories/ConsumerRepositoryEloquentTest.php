<?php

namespace Tests\Unit\Repositories;

use App\Models\Consumer;
use App\Repositories\Consumer\ConsumerRepositoryEloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ConsumerRepositoryEloquentTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(ConsumerRepositoryEloquent::class);
    }

    public function test_it_get_a_consumer_by_id()
    {
        $consumer = Consumer::factory()->create();
        $consumerFromRepo = $this->repository->getConsumerById($consumer->id);

        $this->assertEquals($consumer->toArray(), $consumerFromRepo->toArray());
    }

    public function test_it_trows_an_exception_when_getting_by_id_if_consumer_is_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->getConsumerById(Uuid::uuid4());
    }
}
