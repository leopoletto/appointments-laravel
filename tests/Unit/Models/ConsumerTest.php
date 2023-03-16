<?php

namespace Tests\Unit\Models;

use App\Models\Advertiser;
use App\Models\Appointment;
use App\Models\Consumer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class ConsumerTest extends TestCase
{
    public function test_it_uses_has_factory_trait()
    {
        $uses = class_uses(Consumer::class);

        $this->assertContains(HasFactory::class, $uses);
    }

    public function test_it_uses_uuid_trait()
    {
        $uses = class_uses(Consumer::class);

        $this->assertContains(Uuid::class, $uses);
    }

    public function test_it_has_the_correct_fillable_fields()
    {
        $advertiser = new Consumer;

        $this->assertEquals(['id', 'name', 'zip'], $advertiser->getFillable());
    }

    public function test_it_has_many_appointments()
    {
        $consumer = Consumer::factory()
            ->has(Appointment::factory()->for(Advertiser::factory())->count(10))
            ->create();

        $this->assertInstanceOf(HasMany::class, $consumer->appointments());
        $this->assertCount(10, $consumer->appointments);
        $this->assertContainsOnlyInstancesOf(Appointment::class, $consumer->appointments);
    }
}
