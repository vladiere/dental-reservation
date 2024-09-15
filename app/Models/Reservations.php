<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Carbon;

class Reservations extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "service_id",
        "reservation_datetime",
        "reservation_status",
        "count",
        "reserve_type",
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function services(): BelongsTo
    {
        return $this->belongsTo(Service::class, "service_id");
    }

    public function getReservationDatetimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("M d, Y h:i A") : null;
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("M d, Y h:i A") : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("M d, Y h:i A") : null;
    }
}
