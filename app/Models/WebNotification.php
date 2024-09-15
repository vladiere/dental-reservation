<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Models\User;

class WebNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "appointment_id",
        "web_message",
        "web_date_time",
        "notif_status",
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function getWebDateTimeAttribute($value)
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
