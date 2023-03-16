<?php

namespace Tests\Unit\Models\Traits;

use Tests\TestCase;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UuidTest extends TestCase
{
    public function test_it_returns_false_for_get_increments()
    {
        /** @var App\Models\Traits\Uuid */
        $trait = $this->getMockForTrait(Uuid::class);

        $this->assertFalse($trait->getIncrementing());
    }

    public function test_it_uses_string_as_key_type()
    {
        /** @var App\Models\Traits\Uuid */
        $trait = $this->getMockForTrait(Uuid::class);

        $this->assertEquals('string', $trait->getKeyType());
    }

    public function test_it_defines_the_id_value_using_an_uuid_string()
    {
        Schema::connection('sqlite')->create('dummy_table', function (Blueprint $table) {
            $table->uuid('id');
            $table->timestamps();
        });

        $dummyModel = new class extends Model
        {
            protected $table = 'dummy_table';
            use Uuid;
        };

        $dummyModel->save();

        $this->assertTrue(RamseyUuid::isValid($dummyModel->id));
    }
}
