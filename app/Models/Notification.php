<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;
    use Uuid;
    
    protected $fillable = [
        'id',
        'type',
        'message',
        'advertiser_id',
    ];

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(Advertiser::class);
    }
}
