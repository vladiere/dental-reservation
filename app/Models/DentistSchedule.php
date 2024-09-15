<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DentistSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "sched_days",
        "time_from",
        "time_to",
        "user_id",
        "sched_status",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    // Transform long date to short date
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("m/d/Y") : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("m/d/Y") : null;
    }
}
