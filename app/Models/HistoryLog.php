<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class HistoryLog extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        "entity_id",
        "action_type",
        "actor_role",
        "timestamp",
        "performed_by",
        "affected_user_id",
        "action_scope",
        "status",
        "ip_address",
        "session_id",
        "device_info",
        "old_value",
        "new_value",
        "reason",
        "user_agent",
        "reference_id",
        "geo_location",
        "changeset",
        "event_description",
    ];

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("M d Y h:i A") : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("M d Y h:i A") : null;
    }
}
