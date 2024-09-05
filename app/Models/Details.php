<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Details extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "middle_name",
        "last_name",
        "contact_no",
        "gender",
        "address",
        "dental_clinic_name",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "id");
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("m/d/Y") : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("m/d/Y") : null;
    }
}
