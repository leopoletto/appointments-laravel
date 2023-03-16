<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advertiser extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'id',
        'name'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function notifications():HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function statistics(): HasMany
    {
        return $this->hasMany(Statistic::class);
    }
}
