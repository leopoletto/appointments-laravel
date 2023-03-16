<?php

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid as RamseyUuid;

trait Uuid
{
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) RamseyUuid::uuid4();
        });
    }
}
