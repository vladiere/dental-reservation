<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        "appointment_id",
        "sms_message",
        "sms_date_time",
        "notif_status",
        "user_id",
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
