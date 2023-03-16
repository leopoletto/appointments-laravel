<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;
    use Uuid;

    protected $fillable = [
        'id',
        'weekday',
        'start_time',
        'end_time',
    ];

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(Advertiser::class);
    }
}
