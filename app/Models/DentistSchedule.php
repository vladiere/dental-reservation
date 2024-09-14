<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
