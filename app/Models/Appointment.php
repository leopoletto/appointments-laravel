<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;
    use Uuid;

    const STATUS_PENDING = 'pending';
    const STATUS_PROGRESSING = 'progressing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';

    protected $fillable = [
        'date',
        'start_time',
        'finish_time',
        'duration',
        'price',
        'status',
        'consumer_zip',
        'consumer_id',
        'advertiser_id',
        'started_at',
        'finished_at',
    ];

    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class);
    }

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(Advertiser::class);
    }
}
